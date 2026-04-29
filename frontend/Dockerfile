# ===== BUILD STAGE =====
FROM node:18-alpine AS build

WORKDIR /app

# Копируем только зависимости (ускоряет кэш)
COPY package.json package-lock.json* ./

# Чистим кеш и ставим зависимости
RUN npm cache clean --force \
 && npm install --no-audit --no-fund --legacy-peer-deps

# Копируем весь проект
COPY . .

# Переменные окружения (если нужны)
COPY example.env.production .env.production

# Сборка
RUN npm run build


# ===== RUNTIME STAGE =====
FROM nginx:alpine

# Если у тебя React → build
# Если Vue/Vite → dist (проверь!)
# COPY --from=build /app/build /usr/share/nginx/html
# или:
COPY --from=build /app/dist /usr/share/nginx/html

# Кастомный nginx конфиг (если нужен)
COPY ./000-default.conf /etc/nginx/conf.d/default.conf

EXPOSE 80

CMD ["nginx", "-g", "daemon off;"]