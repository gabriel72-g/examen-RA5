resource "aws_instance" "mi-ec2" {
    ami = "ami-0f9c27b471bdcd702"
    instance_type = "t3.micro"
    key_name = "clave-ec2"
    subnet_id = aws_subnet.public_subnet.id
    vpc_security_group_ids = [aws_security_group.grupo_seguridad.id]
    associate_public_ip_address = true
    user_data = file("user-data.sh")
}