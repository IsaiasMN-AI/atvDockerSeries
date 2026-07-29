## Explicações Preliminares

*   **Retirada do comando CMD no Dockerfile:** Notei que estava sendo copiado para dentro de /app no container da API os arquivos contidos em /api ao fazer o build, porém, ao subir o container, ele criaria a pasta /app/api, contendo os mesmos arquivos que estavam em /app. Para resolver isso, foi necessário retirar o comando do Dockerfile e colocá-lo no docker-compose, trocando app.main:app para main:app, já que ele já estará procurando no WORKDIR /app.
*   **Estrutura de Diretórios da API:** Como os diretórios /database, /models e /routes estavam fora de /api, para não alterar a estrutura do projeto, foram criados os volumes binds extras dentro do serviço "api" para cada uma delas, apontando para /app no container. Dessa forma o main.py em /app consegue acessar seus módulos sem problemas ou redundancias.

# Catálogo de Séries

Este projeto é uma aplicação de cadastro de séries conteinerizada com Docker Compose, contendo um frontend em PHP, uma API em FastAPI e um banco de dados MySQL.

## Instruções de Uso

*   **Como subir o ambiente:** Execute `docker compose up -d --build` na raiz do projeto.
*   **Como parar o ambiente:** Execute `docker compose down`.
*   **Como apagar volumes:** Execute `docker compose down -v` (Isso apagará o banco de dados e todos os dados salvos) ou de forma mais controlada use `docker volumes ls` para listar os volumes e `docker volumes rm <volume>` para remover um volume específico.
*   **Como acessar o frontend:** Abra o navegador e acesse `http://localhost` (ou o IP da vm).
*   **Como testar a API internamente:** Acesse a documentação interativa do FastAPI através de `http://localhost:8000` (a porta 8000 deve ser mapeada no serviço "api" do arquivo docker compose).
*   **Como ver logs:** Execute `docker compose logs` para ver de todos os serviços, ou `docker compose logs [nome-do-serviço]` (ex: `docker compose logs api`) para ver de um container específico.

## Arquitetura de Redes

A infraestrutura foi dividida em três redes distintas para garantir a segurança e isolamento dos serviços:

*   **Como funciona a rede frontend:** Conecta exclusivamente o servidor Web (Nginx/PHP) e expõe a aplicação para a máquina do usuário (host). O usuário externo só tem acesso a essa camada.
*   **Como funciona a rede backend:** É uma rede interna que conecta o serviço do PHP ao serviço da API FastAPI. O frontend utiliza essa rede para consumir os dados sem expor a API publicamente.
*   **Rede banco dados:** Conecta exclusivamente a API ao banco de dados MySQL. Nenhum outro serviço possui acesso a ela.

## Decisões de Arquitetura

*   **Por que o banco não tem porta publicada:** Para garantir a segurança dos dados. Como o banco de dados só precisa se comunicar com a API através da rede interna do Docker, não há necessidade (e é um risco de segurança) expor a porta 3306 para a rede externa.
*   **Por que o PHP usa http://api:8000:** O PHP roda do lado do servidor (backend) dentro do seu próprio container. Ele utiliza a resolução de DNS interna do Docker, onde o nome do serviço `api` é automaticamente traduzido para o IP interno do container da FastAPI na rede `backend`, permitindo a comunicação HTTP na porta em que o Uvicorn está escutando.

