from pydantic import BaseModel

class Serie(BaseModel):
    titulo: str
    genero: str
    ano_nascimento: int
    temporadas: int