#---------------------------------------------------------------------------
# Dockefile to build Docker Image of Apache WebServer running on Ubuntu
#
# Made by Vyacheslav Barakovskiy DevOps IBM-Academy  19-Febrary-2025
#---------------------------------------------------------------------------

FROM ubuntu:16.04

RUN apt-get -y update
RUN apt-get -y install apache2

RUN echo 'Hello World from Docker!' > /var/www/html/index.html


CMD ["/usr/sbin/apache2ctl", "-D","FOREGROUND"]
EXPOSE 80
