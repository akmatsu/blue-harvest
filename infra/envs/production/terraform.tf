terraform {
  required_providers {
    aws = {
      source = "hashicorp/aws"
      version = "~> 5.92"
    }
  }

  backend "s3" {
    bucket = "blueharvest-terraform-state"
    key = "infrastructure/terraform.tfstate"
    region = "us-west-2"
    dynamodb_table = "blueharvest-terraform-locks"
    encrypt = true
  }

  required_version = ">= 1.12"
}