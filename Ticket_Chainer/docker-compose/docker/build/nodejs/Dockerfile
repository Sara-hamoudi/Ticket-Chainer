FROM ubuntu:bionic

ARG UID
ARG GID

WORKDIR /srv/app

RUN apt-get update \ 
    && apt-get install -y --no-install-recommends gnupg2 curl vim ca-certificates net-tools lsof


RUN groupadd -g ${GID} nonroot && \
    useradd -r -u ${UID}  -g nonroot -d /home/nonroot -s /sbin/nologin nonroot && \
    mkdir /home/nonroot && \
    chown -R nonroot:nonroot /home/nonroot

# Bash aliases
RUN echo 'alias ll="ls -alF"' >> /home/nonroot/.bashrc

# Install Node 10.16.2
RUN curl -sL https://deb.nodesource.com/setup_10.x | bash - \
    && apt-get -y --no-install-recommends install nodejs

# Install YARN
RUN curl -sS https://dl.yarnpkg.com/debian/pubkey.gpg | apt-key add - \
    && echo "deb https://dl.yarnpkg.com/debian/ stable main" | tee /etc/apt/sources.list.d/yarn.list \
    && apt-get update && apt-get install -y --no-install-recommends yarn


USER nonroot
EXPOSE 8080