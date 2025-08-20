# 🏋️‍♂️ My Academy - Backend  

**Status:** Projeto em desenvolvimento para fins educacionais.  

Este repositório contém o código-fonte do **backend** para a aplicação *Minha Academia*, uma **API RESTful** projetada para gerenciar usuários, treinos e exercícios de um aplicativo de fitness.  

---

## 🏛️ Arquitetura e Conceitos Aplicados  

- **Arquitetura em Camadas:** a lógica de negócio é separada da lógica de framework.  
- **Controllers:** responsáveis apenas por receber requisições e retornar respostas HTTP.  
- **Services:** onde reside a lógica de negócio principal da aplicação.  
- **Requests:** validação de dados de entrada isolada em classes de FormRequest.  
- **Clean Code & SOLID:** código legível, simples e seguindo princípios de design de software para facilitar futuras modificações.  
- **API-First:** a API é a fonte de dados principal para o aplicativo mobile em Android (*Kotlin/Compose*).  
- **Ambiente de Desenvolvimento com Docker:** totalmente conteinerizado, garantindo um ambiente consistente e fácil de configurar.  

---

## ✨ Funcionalidades  

### 🔐 Autenticação Segura  
- Registro de novos usuários com validação de senha forte.  
- Login com retorno de token de acesso via **Laravel Sanctum**.  
- Endpoint de **Logout** para invalidar tokens de forma segura.  

### 👤 Gerenciamento de Usuário  
- Endpoint para buscar os dados do usuário autenticado.  
- Endpoint para exclusão permanente da conta e de todos os dados associados.  

### 💪 Gerenciamento de Treinos (Workouts)  
- CRUD completo para treinos (**Criar, Ler, Atualizar, Deletar**).  
- Sistema de **Séries (Sets)** associadas a cada treino.  

### 📚 Documentação de API Interativa  
- Geração automática de documentação com **Swagger (OpenAPI)** para fácil visualização e teste dos endpoints.  

---

## 🛠️ Tecnologias Utilizadas  

| **Categoria**        | **Tecnologia**                 |
|-----------------------|--------------------------------|
| **Backend**           | PHP 8.2+ / Laravel 12+         |
| **Banco de Dados**    | MySQL 8                        |
| **Autenticação**      | Laravel Sanctum (API Tokens)   |
| **Ambiente**          | Docker & Docker Compose        |
| **Servidor Web**      | Nginx                          |
| **Testes**            | PHPUnit                        |
| **Documentação API**  | L5-Swagger                     |
| **Qualidade de Código** | Laravel Pint                 |

---
