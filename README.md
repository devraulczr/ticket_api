# Documentação da API

## Visão Geral
Esta API foi desenvolvida para gerenciamento de usuários e tickets. Ela permite autenticação de usuários, gerenciamento de tickets e envio de mensagens.

## Configuração da Conexão com o Banco de Dados
A API se conecta ao banco de dados MySQL com as seguintes credenciais:

- **Host:** `127.0.0.1:3307`
- **Usuário:** `root`
- **Senha:** `""` (vazia)
- **Banco de dados:** `netron_company`

## Headers de Requisição
A API permite requisições de qualquer origem com os seguintes cabeçalhos:
- `Content-Type: application/json`
- `Access-Control-Allow-Origin: *`
- `Access-Control-Allow-Methods: GET, POST, PUT, DELETE`
- `Access-Control-Allow-Headers: Content-Type, Authorization`

## Endpoints Disponíveis
### 1. Login do Usuário
**Endpoint:** `/login`

**Método:** `POST`

**Parâmetros:**
```json
{
  "email": "usuario@example.com",
  "password": "senha123"
}
```

**Respostas:**
- `200 OK` - Login bem-sucedido
- `400 Bad Request` - Email ou senha inválidos
- `404 Not Found` - Usuário não encontrado

---
### 2. Obter ID do Usuário Logado
**Endpoint:** `/get_user_id`

**Método:** `GET`

**Respostas:**
- `200 OK` - Retorna o ID do usuário logado
- `400 Bad Request` - Usuário não logado

---
### 3. Obter Informações do Usuário Logado
**Endpoint:** `/get_user_info`

**Método:** `GET`

**Respostas:**
- `200 OK` - Retorna informações do usuário
- `400 Bad Request` - Usuário não logado
- `404 Not Found` - Nenhum usuário encontrado

---
### 4. Verificar Login
**Endpoint:** `/verify_login`

**Método:** `GET`

**Respostas:**
- `200 OK` - Usuário está logado
- `400 Bad Request` - Usuário não está logado

---
### 5. Logout
**Endpoint:** `/logout`

**Método:** `GET`

**Respostas:**
- `200 OK` - Logout bem-sucedido

---
### 6. Criar Ticket
**Endpoint:** `/create_ticket`

**Método:** `POST`

**Parâmetros:**
```json
{
  "title": "Problema com acesso",
  "description": "Não consigo acessar minha conta"
}
```

**Respostas:**
- `200 OK` - Ticket criado com sucesso
- `400 Bad Request` - Erro ao criar o ticket
- `403 Forbidden` - Usuário não autorizado

---
### 7. Listar Tickets
**Endpoint:** `/list_tickets`

**Método:** `GET`

**Respostas:**
- `200 OK` - Lista de tickets
- `403 Forbidden` - Usuário não autorizado
- `404 Not Found` - Nenhum ticket encontrado

---
### 8. Enviar Mensagem
**Endpoint:** `/send_message`

**Método:** `POST`

**Parâmetros:**
```json
{
  "ticket_id": 1,
  "message": "Preciso de suporte urgente"
}
```

**Respostas:**
- `200 OK` - Mensagem enviada com sucesso
- `400 Bad Request` - Parâmetros inválidos
- `403 Forbidden` - Usuário não autorizado
- `404 Not Found` - Ticket ou usuário não encontrado

---
### 9. Listar Mensagens de um Ticket
**Endpoint:** `/messages`

**Método:** `GET`

**Parâmetros:**
```json
{
  "ticket_id": 1
}
```

**Respostas:**
- `200 OK` - Lista de mensagens do ticket
- `400 Bad Request` - Ticket ID inválido
- `403 Forbidden` - Usuário não autorizado
- `404 Not Found` - Nenhuma mensagem encontrada

---
### 10. Obter Nome do Usuário pelo ID
**Endpoint:** `/get_id_info`

**Método:** `POST`

**Parâmetros:**
```json
{
  "user_id": 1
}
```

**Respostas:**
- `200 OK` - Retorna o nome do usuário
- `404 Not Found` - Usuário não encontrado

## Considerações Finais
- As sessões são utilizadas para autenticação do usuário.
- O acesso é permitido apenas para usuários autenticados.
- Administradores têm permissão para visualizar todos os tickets.
- Recomenda-se a utilização de HTTPS para segurança.

Esta documentação cobre os principais endpoints da API e suas respectivas respostas.

