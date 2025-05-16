# Desafio Técnico - API REST-FULL de restaurante
Uma API RESTful em Laravel para gerenciar operações de restaurante, incluindo autenticação de funcionários, cardápio, mesas, clientes e pedidos.

# Objetivo

Você precisa de uma API REST-FULL para a utilização do restaurante, que pode ser utilizada para celular ou um SPA.

**Sua aplicação DEVE:**

* Fazer login funcionário (garçom):
    * Deve apenas visualizar seus pedidos
* Fazer login funcionário (cozinheiro):
    * Deve visualizar todos os pedidos em andamento e há fazer
* Não precisa ter login cliente
* Cadastro de Clientes (nome, CPF)
* Fazer o cadastro das mesas do restaurante (número da mesa).
* Fazer o cadastro de cardápios (cardápios com os itens do cardápio).
* Fazer o pedido para a mesa do cliente.
* Listar todos os pedidos (filtros: dia, semana, mês, por mesa, por cliente).
* Listar pedidos em andamento, (para o garçom).
* Listar pedidos há fazer e em andamento, (para o cozinheiro).
* Listar por cliente, maior pedido, primeiro pedido, último pedido.

**Tecnologias que devem estar presentes no desafio:**

* Laravel (obrigatório)
* MySQL ou MariaDB

## Pré-requisitos

* Docker instalado.
* Docker Compose instalado.

## Como Executar

1.  Clone o repositório.
2.  Navegue até a raiz do projeto (onde o arquivo `compose.dev.yaml` está localizado)
3.  Execute o seguinte comando no terminal: `docker-compose up -d`
4.  A API estará acessível em `http://localhost:8000`

## Acessando as Rotas da API

Todas as rotas da API estão prefixadas com `/api`.

### Autenticação

Para acessar as rotas protegidas, você precisará obter um token de autenticação obtido através da rota `POST /api/auth/login` que recebe as credenciais do funcionário (e-mail/usuário e senha) e retorna um token.

Após obter o token, você deve incluí-lo no cabeçalho de todas as requisições protegidas usando o esquema de autenticação Bearer:

* **`POST /api/auth/logout`**: Desloga o usuário autenticado.
* **`GET /api/auth/user`**: Retorna os dados do usuário autenticado

### Clientes

* **`GET /api/customers`**: Lista todos os clientes 
* **`POST /api/customers`**: Cadastra um novo cliente 
    * **Body (JSON):** `{"name": "Nome do Cliente", "cpf": "000.000.000-00"}`
* **`GET /api/customers/{customer}`**: Exibe os detalhes de um cliente específico 
* **`PUT /api/customers/{customer}`**: Atualiza os dados de um cliente específico 
    * **Body (JSON):** `{"name": "Novo Nome", "cpf": "111.111.111-11"}`
* **`DELETE /api/customers/{customer}`**: Exclui um cliente específico 
* **`GET /api/customers/{customer}/orders/largest`**: Lista o maior pedido de um cliente específico 
* **`GET /api/customers/{customer}/orders/first`**: Lista o primeiro pedido de um cliente específico 
* **`GET /api/customers/{customer}/orders/latest`**: Lista o último pedido de um cliente específico 

### Mesas

* **`GET /api/tables`**: Lista todas as mesas 
* **`POST /api/tables`**: Cadastra uma nova mesa 
    * **Body (JSON):** `{"number": 10}`
* **`GET /api/tables/{table}`**: Exibe os detalhes de uma mesa específica 
* **`PUT /api/tables/{table}`**: Atualiza os dados de uma mesa específica 
    * **Body (JSON):** `{"number": 12, "status": "occupied"}`
* **`DELETE /api/tables/{table}`**: Exclui uma mesa específica 

### Cardápio

#### Categorias

* **`GET /api/menu/categories`**: Lista todas as categorias do cardápio 
* **`POST /api/menu/categories`**: Cadastra uma nova categoria 
    * **Body (JSON):** `{"name": "Bebidas"}`
* **`GET /api/menu/categories/{category}`**: Exibe os detalhes de uma categoria específica 
* **`PUT /api/menu/categories/{category}`**: Atualiza os dados de uma categoria específica 
    * **Body (JSON):** `{"name": "Sobremesas"}`
* **`DELETE /api/menu/categories/{category}`**: Exclui uma categoria específica 

#### Itens do Cardápio

* **`GET /api/menu/items`**: Lista todos os itens do cardápio 
* **`POST /api/menu/items`**: Cadastra um novo item do cardápio 
    * **Body (JSON):** `{"category_id": 1, "name": "Pão de Queijo", "description": "...", "price": 6.50, "available": true, "preparation_time": 15}`
* **`GET /api/menu/items/{item}`**: Exibe os detalhes de um item específico do cardápio 
* **`PUT /api/menu/items/{item}`**: Atualiza os dados de um item específico do cardápio 
    * **Body (JSON):** `{"price": 7.00, "available": false}`
* **`DELETE /api/menu/items/{item}`**: Exclui um item específico do cardápio 

### Pedidos

* **`GET /api/orders/waiters`**: Lista os pedidos atribuídos ao garçom autenticado (requer autenticação, papel de garçom).
* **`GET /api/orders/cooks`**: Lista os pedidos pendentes e em andamento para o cozinheiro (requer autenticação, papel de cozinheiro).
* **`GET /api/orders`**: Lista todos os pedidos (requer autenticação, com filtros opcionais por query parameters).
    * **Query Parameters (opcionais):**
        * `period`: `day`, `week`, `month`
        * `table_id`: ID da mesa
        * `customer_id`: ID do cliente
* **`POST /api/orders`**: Cria um novo pedido 
    * **Body (JSON):** `{"table_id": 5, "customer_id": 2, "notes": "Sem cebola", "items": [{"menu_item_id": 1, "quantity": 2}, {"menu_item_id": 3, "quantity": 1}]}`
* **`GET /api/orders/{order}`**: Exibe os detalhes de um pedido específico 
* **`PUT /api/orders/{order}`**: Atualiza os dados de um pedido específico 
    * **Body (JSON):** `{"status": "preparing", "notes": "Adicionar urgência"}`
* **`DELETE /api/orders/{order}`**: Exclui um pedido específico 
