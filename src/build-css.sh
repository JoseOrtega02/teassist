#!/bin/bash
# ==========================================
# build-css.sh
# Compila todos los archivos CSS de resources/css
# hacia public/css usando Tailwind CLI
# ==========================================

INPUT_DIR="resources/css"
OUTPUT_DIR="public/css"

# Verifica que tailwindcss esté instalado
if ! [ -x "$(command -v npx)" ]; then
  echo "❌ Error: npx no está instalado. Ejecutá 'npm install' primero."
  exit 1
fi

# Crea la carpeta de salida si no existe
mkdir -p "$OUTPUT_DIR"

# Busca todos los archivos CSS en resources/css
FILES=$(find "$INPUT_DIR" -type f -name "*.css")

# Si no hay archivos, salir
if [ -z "$FILES" ]; then
  echo "⚠️  No se encontraron archivos CSS en $INPUT_DIR"
  exit 0
fi

# Compilar cada archivo individualmente
for FILE in $FILES; do
  BASENAME=$(basename "$FILE")
  OUTFILE="$OUTPUT_DIR/$BASENAME"

  echo "🧩 Compilando $FILE → $OUTFILE"
  npx tailwindcss -i "$FILE" -o "$OUTFILE" --minify
done

echo "✅ Todos los CSS fueron compilados en $OUTPUT_DIR"