<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

# 📦 API de Transferências

Este é um projeto Laravel que simula funcionalidades básicas de um sistema de pagamento, com suporte a usuários comuns e lojistas, transferências com verificação de saldo, autorização externa e envio de notificações assíncronas via fila.

---

## 🚀 Tecnologias

-   PHP 8.2 (via Docker)
-   Laravel 10+
-   PostgreSQL
-   Redis para a fila
-   Docker / Docker Compose

---

## 📁 Estrutura dos Containers

-   `app`: Container principal Laravel
-   `db`: Banco de dados PostgreSQL
-   `redis`: Gerenciador de filas
-   `queue`: Worker Laravel para processar jobs (notificações, etc.)

---

## 📦 Como iniciar o projeto

1. **Clone o repositório**:

```bash
git clone https://github.com/thiagorm28/transferencias-api.git
```

2. **Configure as variáveis de ambiente:**:

```bash
cp .env.example .env
```

3. **Suba os containers com Docker:**:

```bash
docker-compose up -d --build
```

4. **Acesse o container app e execute as migrations:**:

```bash
docker exec -it transferencias_api bash
php artisan migrate
```

## 📚 Documentação da API

Você pode ver a documentação da API na URL:

```bash
http://localhost:8000/docs/
```

## 🔍 Testando a API

Você pode testar endpoints usando ferramentas como Postman ou Insomnia. O projeto roda na porta 8000, então você pode acessar via:

```bash
http://localhost:8000/api
```

## 🧪 Rodar os testes da API

Você pode os testes da API usando o comando (banco de dados de teste já configurado, é iniciado dentro do docker, e configurado com base no .env.testing):

```bash
docker exec -it transferencias_api php artisan test
```
