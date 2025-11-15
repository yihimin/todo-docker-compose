# MySQL 8.0 이미지 사용
FROM mysql:8.0

# 환경 변수 설정
ENV MYSQL_ROOT_PASSWORD=example
ENV MYSQL_DATABASE=todo_app

# 초기화 SQL 스크립트 복사
# /docker-entrypoint-initdb.d/ 디렉토리의 .sql 파일은 자동 실행됨
COPY init.sql /docker-entrypoint-initdb.d/

# SQL 파일 실행 권한 부여
RUN chmod +r /docker-entrypoint-initdb.d/init.sql

# 포트 3306 노출
EXPOSE 3306
