terraform {
  required_providers {
    aws={
        source = "hashicorp/aws"
        version = "~>5.0"
    }
  }
}

provider "aws" {
  region = "us-east-1"
}

resource "tls_private_key" "nueva-key" {
    algorithm = "RSA"
    rsa_bits = 4096
}

resource "aws_key_pair" "clave" {
    key_name = "clave-ec2"
    public_key = tls_private_key.nueva-key.public_key_openssh
}

resource "aws_s3_bucket" "s3" {
    bucket = "mi-bucket-gabriel-2"
}