# PHP 8.2 Apache 이미지 사용
FROM php:8.2-apache

# mysqli 확장 설치 (MySQL 연결에 필요)
RUN docker-php-ext-install mysqli && docker-php-ext-enable mysqli

# 작업 디렉토리 설정
WORKDIR /var/www/html

# 현재 디렉토리의 모든 파일을 컨테이너의 /var/www/html/로 복사
COPY . /var/www/html/

# 권한 설정 (www-data 사용자가 읽고 실행할 수 있게)
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html

# 포트 80 노출
EXPOSE 80

# Apache 시작
CMD ["apache2-foreground"]
