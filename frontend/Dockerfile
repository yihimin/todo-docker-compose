# Nginx 이미지 사용
FROM nginx:alpine

# Nginx 설정 파일 복사 (CORS 허용)
COPY nginx.conf /etc/nginx/conf.d/default.conf

# 프론트엔드 파일 복사
COPY index.html style.css app.js /usr/share/nginx/html/

# 파일 권한 설정
RUN chmod -R 644 /usr/share/nginx/html/* && \
    chmod 755 /usr/share/nginx/html

# 포트 80 노출
EXPOSE 80

# Nginx 실행
CMD ["nginx", "-g", "daemon off;"]
