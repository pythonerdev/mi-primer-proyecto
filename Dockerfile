FROM php:7.2-apache

WORKDIR /app
COPY . .
RUN yarn install --production

CMD ["mode","/app/src/index.js"]
