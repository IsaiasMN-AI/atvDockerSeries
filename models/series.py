from pydantic import BaseModel
from typing import Optional

class Serie(BaseModel):
    id: Optional[int] = None
    titulo: str
    genero: str
    ano_lancamento: int
    temporadas: int