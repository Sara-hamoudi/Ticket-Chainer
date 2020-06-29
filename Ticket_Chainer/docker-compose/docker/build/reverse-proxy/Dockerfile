FROM nginx:latest

ARG ENV

COPY ./nginx.conf /etc/nginx/nginx.conf
COPY ./.htpasswd /etc/nginx/.htpasswd
ADD sites-available/${ENV} /etc/nginx/sites-enabled


