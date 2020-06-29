FROM debian:stretch
RUN apt-get update && apt-get install -y nginx vim nano

ARG ENV

COPY nginx.conf /etc/nginx/nginx.conf

ADD sites-enabled /etc/nginx/sites-enabled
RUN rm /etc/nginx/sites-enabled/default

WORKDIR /var/www

EXPOSE 80

CMD ["nginx"]
