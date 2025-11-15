# 📝 Todo List - Docker Compose 기반 웹서비스

> **과제명:** 도커 컴포즈 기반 Todo List 웹서비스 운영
> **개발환경:** PHP 8.2 + MySQL 8.0 + Apache + Docker Compose

---

## 🎯 프로젝트 개요

이 프로젝트는 Docker Compose를 활용하여 Todo List 웹 애플리케이션을 구축하고 운영하는 과제입니다.
PHP와 MySQL을 사용하며, 백엔드와 데이터베이스를 별도의 컨테이너로 분리하여 관리합니다.

---

## 🏗️ 시스템 구조

```
┌─────────────────┐
│   사용자 브라우저   │
└────────┬────────┘
         │ HTTP (Port 3001)
         ▼
┌─────────────────┐
│ Frontend 컨테이너 │
│     (Nginx)     │
└────────┬────────┘
         │ API (Port 8080)
         ▼
┌─────────────────┐
│  Backend 컨테이너  │
│  (PHP + Apache) │
└────────┬────────┘
         │ MySQL Protocol
         ▼
┌─────────────────┐
│   DB 컨테이너     │
│   (MySQL 8.0)   │
└─────────────────┘
```

### 컨테이너 구성

1. **frontend** - Nginx 웹 서버
   - 포트: 3001:80
   - 역할: 정적 파일 제공 (HTML, CSS, JavaScript)

2. **backend** - PHP 8.2 + Apache API 서버
   - 포트: 8080:80
   - 역할: RESTful API 제공 및 비즈니스 로직 처리

3. **db** - MySQL 8.0 데이터베이스
   - 포트: 3306 (내부)
   - 역할: Todo 데이터 저장 및 관리

---

## 📂 프로젝트 구조

```
todo-docker-compose/
├── docker-compose.yml    # Docker Compose 설정 파일
├── README.md            # 프로젝트 설명서
├── frontend/            # 프론트엔드 디렉토리
│   ├── Dockerfile       # 프론트엔드 컨테이너 이미지 빌드 파일
│   ├── nginx.conf       # Nginx 설정 파일
│   ├── index.html       # 메인 HTML 파일
│   ├── style.css        # 스타일시트
│   └── app.js           # JavaScript 파일 (UI 로직)
├── backend/             # 백엔드 디렉토리
│   ├── Dockerfile       # 백엔드 컨테이너 이미지 빌드 파일
│   └── api.php          # RESTful API 엔드포인트
└── db/                  # 데이터베이스 디렉토리
    ├── Dockerfile       # DB 컨테이너 이미지 빌드 파일
    └── init.sql         # 데이터베이스 초기화 스크립트
```

---

## 🚀 실행 방법

### 1️⃣ 사전 준비

Docker와 Docker Compose가 설치되어 있어야 합니다.

```bash
# Docker 버전 확인
docker --version

# Docker Compose 버전 확인
docker-compose --version
```

### 2️⃣ 프로젝트 실행

```bash
# 프로젝트 디렉토리로 이동
cd todo-docker-compose

# Docker Compose로 컨테이너 빌드 및 실행
docker-compose up --build

# 또는 백그라운드 실행
docker-compose up -d --build
```

### 3️⃣ 웹 애플리케이션 접속

브라우저에서 다음 주소로 접속합니다:

```
http://localhost:3001
```

**참고**: 백엔드 API는 `http://localhost:8080`에서 직접 접근 가능합니다.

### 4️⃣ 컨테이너 중지

```bash
# 컨테이너 중지
docker-compose down

# 컨테이너 중지 + 볼륨 삭제 (데이터 완전 삭제)
docker-compose down -v
```

---

## 🔧 주요 기능

1. ✅ **할 일 추가** - 새로운 Todo 항목 추가
2. ✅ **할 일 완료** - 체크박스 클릭으로 완료/미완료 토글
3. ✅ **할 일 삭제** - 삭제 버튼으로 항목 제거
4. ✅ **통계 표시** - 전체/완료/남은 할 일 개수 표시
5. ✅ **반응형 UI** - 모바일/태블릿/데스크톱 대응

---

## 🔍 주요 파일 설명

### docker-compose.yml

Docker Compose 설정 파일로, 3개의 컨테이너를 한 번에 관리합니다.

```yaml
services:
  frontend:  # Nginx 프론트엔드 컨테이너
  backend:   # PHP 백엔드 API 컨테이너
  db:        # MySQL 데이터베이스 컨테이너

networks:    # 컨테이너 간 통신 네트워크
volumes:     # 데이터 영속성 볼륨
```

### frontend/Dockerfile

Nginx 웹 서버 이미지를 빌드합니다.

```dockerfile
FROM nginx:alpine
COPY nginx.conf /etc/nginx/conf.d/default.conf
COPY . /usr/share/nginx/html/
```

### frontend/nginx.conf

Nginx 설정 파일로 프론트엔드 라우팅 및 프록시 설정을 담당합니다.

### backend/Dockerfile

PHP + Apache API 서버 이미지를 빌드합니다.

```dockerfile
FROM php:8.2-apache
RUN docker-php-ext-install mysqli
COPY . /var/www/html/
```

### backend/api.php

RESTful API 엔드포인트를 제공합니다.

- MySQL 연결 및 데이터 CRUD 처리
- JSON 형식의 응답 반환
- GET, POST, PUT, DELETE 요청 처리

### frontend/app.js

프론트엔드 JavaScript 파일입니다.

- API 호출 및 데이터 처리
- DOM 조작 및 이벤트 핸들링
- UI 업데이트 로직

### db/Dockerfile

MySQL 데이터베이스 이미지를 빌드하고 초기화 스크립트를 포함합니다.

```dockerfile
FROM mysql:8.0
COPY init.sql /docker-entrypoint-initdb.d/
```

### db/init.sql

데이터베이스 초기화 SQL 스크립트입니다.

```sql
CREATE DATABASE todo_app;
CREATE TABLE todos (
  id INT AUTO_INCREMENT PRIMARY KEY,
  task VARCHAR(255) NOT NULL,
  completed BOOLEAN DEFAULT FALSE,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
```

---

## 🛠️ 트러블슈팅

### ❌ 포트가 이미 사용 중인 경우

`docker-compose.yml` 파일에서 포트 번호를 변경합니다:

```yaml
frontend:
  ports:
    - "3002:80"  # 3001 대신 3002 사용

backend:
  ports:
    - "9090:80"  # 8080 대신 9090 사용
```

### ❌ 데이터베이스 연결 실패

1. DB 컨테이너가 완전히 시작될 때까지 기다립니다 (약 30초)
2. 다음 명령어로 DB 상태 확인:

```bash
docker-compose logs db
```

### ❌ 데이터가 사라지는 경우

`docker-compose down -v` 명령은 볼륨을 삭제합니다.
데이터를 유지하려면 `-v` 옵션 없이 실행하세요:

```bash
docker-compose down
```

---

## 📊 데이터베이스 직접 확인

MySQL 컨테이너에 직접 접속하여 데이터를 확인할 수 있습니다:

```bash
# MySQL 컨테이너 접속
docker exec -it todo-db mysql -u root -pexample

# SQL 실행
USE todo_app;
SELECT * FROM todos;
```

---

## 📝 개발 환경

- **Frontend**: Nginx (Alpine) + HTML5 + CSS3 + JavaScript (ES6+)
- **Backend**: PHP 8.2 + Apache 2.4
- **Database**: MySQL 8.0
- **Containerization**: Docker + Docker Compose
- **OS**: Linux (Docker 컨테이너)
- **Architecture**: 3-Tier (Frontend - Backend - Database)

---

## 🎓 학습 포인트

이 프로젝트를 통해 다음을 학습할 수 있습니다:

1. ✅ Docker Compose를 활용한 멀티 컨테이너 애플리케이션 구성
2. ✅ 3-Tier 아키텍처 설계 및 구현 (Frontend - Backend - Database 분리)
3. ✅ RESTful API 설계 및 구현
4. ✅ Nginx를 활용한 정적 파일 서빙
5. ✅ PHP와 MySQL 연동 방법
6. ✅ Dockerfile 작성 및 이미지 빌드
7. ✅ 컨테이너 간 네트워크 통신
8. ✅ Docker 볼륨을 통한 데이터 영속성 관리

---

## 📧 제출 정보

- **제출 형식**: ZIP 압축 파일 + PDF 보고서
- **이메일**: namhw@induk.ac.kr
- **마감일**: 2024년 11월 16일 (토) 23:59

---

## 📄 라이선스

이 프로젝트는 교육 목적으로 제작되었습니다.

---

## 👨‍💻 개발자

- 학번: [학번 입력]
- 이름: [이름 입력]
- 과목: 컨테이너 기반 가상화 실습

---

**Happy Coding! 🚀**
