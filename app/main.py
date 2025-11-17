from fastapi import FastAPI
from routes.series import router as series_router
from database.series_db import criar_tabela_series


app = FastAPI()

criar_tabela_series()

app.include_router(series_router)