Minha Academia - Backend 🏋️‍♂️
Status: Projeto em desenvolvimento para fins educacionais.

Este repositório contém o código-fonte do backend para a aplicação Minha Academia, uma API RESTful projetada para gerenciar usuários, treinos e exercícios de um aplicativo de fitness.

O principal objetivo deste projeto é servir como um exemplo prático de construção de uma API robusta com Laravel, aplicando conceitos de Clean Code, arquitetura em camadas e as melhores práticas do ecossistema PHP moderno.

🏛️ Arquitetura e Conceitos Aplicados
A estrutura do projeto foi pensada para ser escalável, testável e de fácil manutenção, seguindo os seguintes princípios:

Arquitetura em Camadas: A lógica de negócio é separada da lógica de framework.

Controllers: Responsáveis apenas por receber requisições e retornar respostas HTTP.

Services: Onde reside a lógica de negócio principal da aplicação.

Requests: Validação de dados de entrada é isolada em classes de FormRequest.

Clean Code & SOLID: O código busca ser legível, simples e seguir os princípios de design de software para facilitar futuras modificações.

API-First: A API foi desenhada para ser a fonte de dados principal para o aplicativo mobile em Android (Kotlin/Compose) (adicione o link do seu app aqui se quiser).

Ambiente de Desenvolvimento com Docker: O projeto é totalmente conteinerizado, garantindo um ambiente de desenvolvimento consistente e fácil de configurar.

✨ Funcionalidades
🔐 Autenticação Segura:

Registro de novos usuários com validação de senha forte.

Login com retorno de token de acesso via Laravel Sanctum.

Endpoint de Logout para invalidar tokens de forma segura.

👤 Gerenciamento de Usuário:

Endpoint para buscar os dados do usuário autenticado.

Endpoint para exclusão permanente da conta e de todos os dados associados.

💪 Gerenciamento de Treinos (Workouts):

CRUD completo para treinos (Criar, Ler, Atualizar, Deletar).

Sistema de Séries (Sets) associadas a cada treino.

📚 Documentação de API Interativa:

Geração automática de documentação com Swagger (OpenAPI) para fácil visualização e teste dos endpoints.

🛠️ Tecnologias Utilizadas
Categoria	Tecnologia
Backend	PHP 8.2+ / Laravel 12+
Banco de Dados	MySQL 8
Autenticação	Laravel Sanctum (API Tokens)
Ambiente	Docker & Docker Compose
Servidor Web	Nginx
Testes	PHPUnit
Documentação API	L5-Swagger
Qualidade de Código	Laravel Pint