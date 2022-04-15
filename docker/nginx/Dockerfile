FROM nginx:latest

ADD ./conf.d /etc/nginx/conf.d
ADD ./public /var/www/public

RUN apt update && apt upgrade -y && apt install -y vim
