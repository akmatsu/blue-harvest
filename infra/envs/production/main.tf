locals {
  tags = merge(var.tags, {
    project     = var.project_name
    environment = var.environment
  })

  name_prefix = "${var.project_name}-${var.environment}"
  vpc_id      = "vpc-9b9e60e3"
}

resource "aws_instance" "web_server" {
  ami = "ami-0cf2b4e024cdb6960"
  instance_type = "t3a.large"
  tags = merge(local.tags, {
    Name = "${local.name_prefix}-web-server"
  })

  vpc_security_group_ids = [aws_security_group.web_server_sg.id]

  root_block_device {
    tags = merge(local.tags, {
      Name = "${local.name_prefix}-web-server-root-disk"
    })
  }
}

resource "aws_db_instance" "database" {
  instance_class = "db.t3.small"
  storage_encrypted = true
  tags = merge(local.tags, {
    Name = "${local.name_prefix}-database"
  })
  skip_final_snapshot = true
  monitoring_interval = 60
  copy_tags_to_snapshot = true
  deletion_protection = true
  allocated_storage = 20
  max_allocated_storage = 100
  vpc_security_group_ids = [
    aws_security_group.web_server_sg.id,
    aws_security_group.db_sg.id,
  ]
}

resource "aws_cloudfront_distribution" "image_cdn" {
  enabled = true
  tags = merge(local.tags, {
    Name = "${local.name_prefix}-image-cdn"
  })

  http_version = "http2and3"
  is_ipv6_enabled = true
  price_class = "PriceClass_100"
  default_cache_behavior {
    cache_policy_id = "658327ea-f89d-4fab-a63d-7e88639e58f6"
    allowed_methods = [
      "DELETE",
      "GET",
      "HEAD",
      "OPTIONS",
      "PATCH",
      "POST",
      "PUT",
    ]
    cached_methods = [
      "GET",
      "HEAD",
    ]
    compress = true
    default_ttl = 0
    max_ttl = 0
    min_ttl = 0
    target_origin_id = "blue-harvest-images.s3.us-west-2.amazonaws.com"
    viewer_protocol_policy = "allow-all"
    grpc_config {
      enabled = false
    }
  }

  viewer_certificate {
    cloudfront_default_certificate = true
    minimum_protocol_version = "TLSv1"
  }

  restrictions {
    geo_restriction {
      locations = []
      restriction_type = "none"
    }
  }

  origin {
    domain_name = aws_s3_bucket.image_storage.bucket_regional_domain_name
    origin_id = aws_s3_bucket.image_storage.bucket_regional_domain_name
  }
}

resource "aws_s3_bucket" "image_storage" {
  bucket = "blue-harvest-images"
  bucket_prefix = null
  object_lock_enabled = false
  tags = merge(local.tags, {
    Name = "${local.name_prefix}-image-storage"
  })
}

resource "aws_s3_bucket_public_access_block" "allow_public_access" {
  bucket = aws_s3_bucket.image_storage.id

  block_public_acls       = false
  block_public_policy     = false
  ignore_public_acls      = false
  restrict_public_buckets = false
}

resource "aws_s3_bucket_ownership_controls" "image_bucket_ownership" {
  bucket = aws_s3_bucket.image_storage.id

  rule {
    object_ownership = "BucketOwnerPreferred"
  }
}

resource "aws_s3_bucket_acl" "public_read_acl" {
  bucket = aws_s3_bucket.image_storage.id
  depends_on = [
    aws_s3_bucket_public_access_block.allow_public_access, 
    aws_s3_bucket_ownership_controls.image_bucket_ownership
  ]
  acl    = "public-read"
}

resource "aws_s3_bucket_policy" "allow_public_read_access" {
  bucket = aws_s3_bucket.image_storage.id
  policy = data.aws_iam_policy_document.public_read_policy.json
}

data "aws_iam_policy_document" "public_read_policy" {
  statement {
    actions = [
      "s3:GetObject",
    ]
    principals {
      type = "AWS"
      identifiers = ["*"]
    }
    resources = [
      "${aws_s3_bucket.image_storage.arn}/*",
    ]
    effect = "Allow"
    sid = "PublicReadGetObject"
  }
  version = "2012-10-17"
}

resource "aws_security_group" "web_server_sg" {
  tags = merge(local.tags, {
    Name = "${local.name_prefix}-web-server-sg"
  })
  description = "Blue Harvest web server security group"
  vpc_id = local.vpc_id
  name       = "${local.name_prefix}-web-server-sg"

  egress = [
            {
            cidr_blocks      = [
                "0.0.0.0/0",
            ]
            description      = null
            from_port        = 0
            ipv6_cidr_blocks = []
            prefix_list_ids  = []
            protocol         = "-1"
            security_groups  = []
            self             = false
            to_port          = 0
        },
  ]
   ingress     = [
        {
            cidr_blocks      = [
                "0.0.0.0/0",
            ]
            description      = null
            from_port        = 0
            ipv6_cidr_blocks = []
            prefix_list_ids  = []
            protocol         = "tcp"
            security_groups  = []
            self             = false
            to_port          = 65535
        },
        {
            cidr_blocks      = [
                "0.0.0.0/0",
            ]
            description      = null
            from_port        = 0
            ipv6_cidr_blocks = []
            prefix_list_ids  = []
            protocol         = "udp"
            security_groups  = []
            self             = false
            to_port          = 65535
        },
        {
            cidr_blocks      = [
                "216.137.207.3/32",
            ]
            description      = null
            from_port        = 5432
            ipv6_cidr_blocks = []
            prefix_list_ids  = []
            protocol         = "tcp"
            security_groups  = []
            self             = false
            to_port          = 5432
        },
        {
            cidr_blocks      = []
            description      = null
            from_port        = 0
            ipv6_cidr_blocks = []
            prefix_list_ids  = []
            protocol         = "-1"
            security_groups  = [
                aws_security_group.db_sg.id,
            ]
            self             = false
            to_port          = 0
        },
    ]
}

resource "aws_security_group" "db_sg" {
    description = "database security group"
    egress      = [
        {
            cidr_blocks      = [
                "0.0.0.0/0",
            ]
            description      = null
            from_port        = 0
            ipv6_cidr_blocks = []
            prefix_list_ids  = []
            protocol         = "-1"
            security_groups  = []
            self             = false
            to_port          = 0
        },
    ]
    ingress     = [
        {
            cidr_blocks      = [
                "216.137.207.3/32",
            ]
            description      = null
            from_port        = 5432
            ipv6_cidr_blocks = []
            prefix_list_ids  = []
            protocol         = "tcp"
            security_groups  = []
            self             = false
            to_port          = 5432
        },
        {
            cidr_blocks      = []
            description      = null
            from_port        = 0
            ipv6_cidr_blocks = []
            prefix_list_ids  = []
            protocol         = "-1"
            security_groups  = []
            self             = true
            to_port          = 0
        },
    ]
    name        = "${local.name_prefix}-db-sg"
    name_prefix = null
    tags = merge(local.tags, {
        Name = "${local.name_prefix}-db-sg"
    })
    vpc_id      = local.vpc_id
}