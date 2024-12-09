FROM nginx:alpine

WORKDIR /usr/share/nginx/html

# Copy nginx configuration
COPY nginx.conf /etc/nginx/conf.d/default.conf

# Copy static files
COPY public/ .

# Add health check
HEALTHCHECK --interval=30s --timeout=3s \
    CMD wget -q --spider http://localhost/health || exit 1

EXPOSE 80 