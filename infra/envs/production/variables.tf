variable "aws_region" {
  description = "The AWS region to deploy resources in"
  type        = string
  default     = "us-west-2"
}

variable "tags" {
  description = "Common tags to apply to all resources"
  type        = map(string)
  default = {
    managed_by = "terraform"
  }
}

variable "environment" {
  description = "The environment for the deployment"
  type        = string
  default     = "prod"
}

variable "project_name" {
  description = "The name of the project"
  type        = string
  default     = "blue harvest"
}