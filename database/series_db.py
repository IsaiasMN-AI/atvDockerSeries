import sqlite3
from typing import Optional, List, Dict

DB_NAME = "series.db"


def get_conexao():
    return sqlite3.connect(DB_NAME)


def criar_tabela_series():
    conexao = get_conexao()
    cursor = conexao.cursor()
    cursor.execute("""
        CREATE TABLE IF NOT EXISTS series (
            titulo TEXT PRIMARY KEY,
            genero TEXT,
            ano_lancamento INTEGER,
            temporadas INTEGER
        )
    """)
    conexao.commit()
    conexao.close()


def inserir_serie(serie: Dict):
    conexao = get_conexao()
    cursor = conexao.cursor()
    cursor.execute(
        """
        INSERT INTO series (titulo, genero, ano_lancamento, temporadas)
        VALUES (?, ?, ?, ?)
        """,
        (serie["titulo"], serie["genero"], serie["ano_lancamento"], serie["temporadas"])
    )
    conexao.commit()
    conexao.close()


def listar_series() -> List[Dict]:
    conexao = get_conexao()
    cursor = conexao.cursor()
    cursor.execute("SELECT titulo, genero, ano_lancamento, temporadas FROM series")
    rows = cursor.fetchall()
    conexao.close()

    return [
        {
            "titulo": r[0],
            "genero": r[1],
            "ano_lancamento": r[2],
            "temporadas": r[3],
        }
        for r in rows
    ]


def buscar_serie_por_titulo(titulo: str) -> Optional[Dict]:
    conexao = get_conexao()
    cursor = conexao.cursor()
    cursor.execute(
        """
        SELECT titulo, genero, ano_lancamento, temporadas
        FROM series
        WHERE LOWER(titulo) = LOWER(?)
        """,
        (titulo,)
    )
    row = cursor.fetchone()
    conexao.close()

    if row is None:
        return None

    return {
        "titulo": row[0],
        "genero": row[1],
        "ano_lancamento": row[2],
        "temporadas": row[3],
    }
