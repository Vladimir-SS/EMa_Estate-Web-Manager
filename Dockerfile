# TODO: For production use the one under
# FROM php:cli-alpine3.16

FROM php:8.1.9RC1-cli-buster

# Node for building (removed in production)
# The code was taken from here (https://stackoverflow.com/questions/36399848/install-node-in-dockerfile)
ENV NODE_VERSION=16.16.0
RUN apt install -y curl
RUN curl -o- https://raw.githubusercontent.com/nvm-sh/nvm/v0.39.1/install.sh | bash
ENV NVM_DIR=/root/.nvm
RUN . "$NVM_DIR/nvm.sh" && nvm install ${NODE_VERSION}
RUN . "$NVM_DIR/nvm.sh" && nvm use v${NODE_VERSION}
RUN . "$NVM_DIR/nvm.sh" && nvm alias default v${NODE_VERSION}
ENV PATH="/root/.nvm/versions/node/v${NODE_VERSION}/bin/:${PATH}"

# Should give an error if there is a instalation error :) Like it happened in Alpine
RUN node --version
RUN npm --version

COPY . /usr/src/ema
WORKDIR /usr/src/ema

RUN npm ci
