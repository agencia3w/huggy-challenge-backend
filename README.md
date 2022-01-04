> ### API em padrão RESTFul voltada para a gestão de leitores. Contém o CRUD de usuários, leitores e livros, integração com CRM PipeRun e é possível informar os livros que foram lidos, esta informação é armazenada em Cache com o Redis.

----------

## Instalação

Por favor verifique a [documentação oficial](https://laravel.com/docs/8.x/installation) do Laravel antes de instalar.

Clone o repositório

    git clone https://github.com/agencia3w/huggy-challenge-backend.git

Acesse a pasta do repositório

    cd huggy-challenge-backend

Instale todas as dependências utilizando o composer

    composer install

Faça uma cópia do arquivo .env.example para .env e informe as configurações referentes ao banco de dados, Redis e email

    cp .env.example .env ou copy .env.example .env (cmd)

Gere uma chave para a aplicação

    php artisan key:generate

Gere um JWT_SECRET

    php artisan jwt:generate

Execute a migração da base de dados (**É necessário configurar as variáveis DB_ * no arquivo .env**)

    php artisan migrate

# Testando a API

Inicie o servidor de desenvolvimento local

    php artisan serve

A API pode ser acessada em

    http://localhost:8000/api

Utilize o [Insomnia](https://insomnia.rest/download) ou [Postman](https://www.postman.com/downloads/) para testar a API

**Headers**

**Key**             | **Value**         |
|------------------	|------------------	|
| Content-Type     	| application/json 	|
| Accept 	        | application/json  |
| Authorization    	| Token {JWT}      	|


----------

## Integração Huggy
[Acesse o Chat para testar a API](https://huggy.agencia3w.com.br/)
