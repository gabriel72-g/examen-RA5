resource "aws_vpc" "mi-vpc" {
    cidr_block = "10.0.0.0/16"
}
resource "aws_subnet" "public_subnet" {
  vpc_id = aws_vpc.mi-vpc.id
  cidr_block = "10.0.1.0/24"
  availability_zone = "us-east-1a"
    map_customer_owned_ip_on_launch = true
}

resource "aws_internet_gateway" "igw" {
  vpc_id = aws_vpc.mi-vpc.id
}

resource "aws_route_table" "public_rt" {
  vpc_id = aws_vpc.mi-vpc.id
  route {
    cidr_block = "0.0.0.0/0"
    gateway_id = aws_internet_gateway.igw.id
  }
}