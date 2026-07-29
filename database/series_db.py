import os
import pymysql
from typing import Dict, List, Optional
from sqlalchemy import create_engine
from sqlalchemy.orm import sessionmaker

DATABASE_URL = os.getenv("DATABASE_URL")

# Trava de segurança: impede que a API inicie se a variável não for encontrada
if not DATABASE_URL:
    raise ValueError("A variável de ambiente 'DATABASE_URL' não foi encontrada!")

engine = create_engine(DATABASE_URL)
SessionLocal = sessionmaker(autocommit=False, autoflush=False, bind=engine)

# Pegamos as credenciais usando as variáveis de ambiente (as mesmas do docker-compose)
DB_HOST = os.getenv("DB_HOST", "mysql_db")
DB_USER = os.getenv("DB_USER", "root")
DB_PASSWORD = os.getenv("DB_PASSWORD", "sua_senha")
DB_NAME = os.getenv("DB_NAME", "catalogo_series")


def get_conexao():
    # Cria e retorna a conexão com o banco de dados MySQL
    return pymysql.connect(
        host=DB_HOST,
        user=DB_USER,
        password=DB_PASSWORD,
        database=DB_NAME
    )


def criar_tabela_series():
    conexao = get_conexao()
    cursor = conexao.cursor()
    cursor.execute("""
        CREATE TABLE IF NOT EXISTS series (
            id INT AUTO_INCREMENT PRIMARY KEY,
            titulo VARCHAR(255) NOT NULL,
            genero VARCHAR(255),
            ano_lancamento INT,
            temporadas INT
        )
    """)
    conexao.commit()
    conexao.close()


def inserir_serie(serie: Dict):
    conexao = get_conexao()
    cursor = conexao.cursor()
    # Para o pymysql, usamos %s no lugar de ?
    cursor.execute(
        """
        INSERT INTO series (titulo, genero, ano_lancamento, temporadas)
        VALUES (%s, %s, %s, %s)
        """,
        (serie["titulo"], serie["genero"], serie["ano_lancamento"], serie["temporadas"])
    )
    conexao.commit()
    conexao.close()


def listar_series() -> List[Dict]:
    conexao = get_conexao()
    cursor = conexao.cursor()
    # Adicionamos o 'id' na query
    cursor.execute("SELECT id, titulo, genero, ano_lancamento, temporadas FROM series")
    rows = cursor.fetchall()
    conexao.close()

    return [
        {
            "id": r[0],
            "titulo": r[1],
            "genero": r[2],
            "ano_lancamento": r[3],
            "temporadas": r[4],
        }
        for r in rows
    ]

def buscar_serie_por_id(id_serie: int) -> Optional[Dict]:
    conexao = get_conexao()
    cursor = conexao.cursor()
    cursor.execute(
        """
        SELECT id, titulo, genero, ano_lancamento, temporadas 
        FROM series 
        WHERE id = %s
        """,
        (id_serie,)
    )
    row = cursor.fetchone()
    conexao.close()

    if row is None:
        return None

    return {
        "id": row[0],
        "titulo": row[1],
        "genero": row[2],
        "ano_lancamento": row[3],
        "temporadas": row[4],
    }

def buscar_serie_por_titulo(titulo: str) -> Optional[Dict]:
    conexao = get_conexao()
    cursor = conexao.cursor()
    # Adicionamos o 'id' no SELECT para bater com o novo formato do banco
    cursor.execute(
        """
        SELECT id, titulo, genero, ano_lancamento, temporadas
        FROM series
        WHERE LOWER(titulo) = LOWER(%s)
        """,
        (titulo,)
    )
    row = cursor.fetchone()
    conexao.close()

    if row is None:
        return None

    return {
        "id": row[0],
        "titulo": row[1],
        "genero": row[2],
        "ano_lancamento": row[3],
        "temporadas": row[4],
    }

def atualizar_serie(id_serie: int, serie: Dict) -> bool:
    conexao = get_conexao()
    cursor = conexao.cursor()
    cursor.execute(
        """
        UPDATE series 
        SET titulo = %s, genero = %s, ano_lancamento = %s, temporadas = %s 
        WHERE id = %s
        """,
        (serie["titulo"], serie["genero"], serie["ano_lancamento"], serie["temporadas"], id_serie)
    )
    linhas_afetadas = cursor.rowcount
    conexao.commit()
    conexao.close()
    
    # Retorna True se atualizou alguma linha, False se o ID não existir
    return linhas_afetadas > 0

def deletar_serie(id_serie: int) -> bool:
    conexao = get_conexao()
    cursor = conexao.cursor()
    cursor.execute("DELETE FROM series WHERE id = %s", (id_serie,))
    linhas_afetadas = cursor.rowcount
    conexao.commit()
    conexao.close()
    
    # Retorna True se deletou alguma linha, False se o ID não existir
    return linhas_afetadas > 0