import os
import json
import asyncio
import ollama
import edge_tts
from moviepy import ImageClip, AudioFileClip, concatenate_videoclips
from PIL import Image, ImageDraw, ImageFont

OUTPUT_DIR = "./output"
os.makedirs(OUTPUT_DIR, exist_ok=True)

def generar_guion(tema):
    print("📝 Agente 1: Escribiendo guion...")
    prompt_sistema = "Eres un guionista para The Difference. Responde SOLO con un objeto JSON valido. Debe tener una clave 'titulo' y una clave 'escenas' que sea una lista de 4 objetos. Cada objeto debe tener: 'fase', 'narracion', 'prompt_visual', 'duracion_segundos'."
    prompt_usuario = "Tema: " + tema + ". Genera el JSON."
    
    try:
        respuesta = ollama.chat(model='llama3', messages=[
            {'role': 'system', 'content': prompt_sistema},
            {'role': 'user', 'content': prompt_usuario}
        ])
        
        texto = respuesta['message']['content'].strip()
        
        # Limpieza robusta de bloques de código markdown
        if "```json" in texto:
            texto = texto.split("```json")[1].split("```")[0].strip()
        elif "```" in texto:
            texto = texto.split("```")[1].split("```")[0].strip()
            
        guion = json.loads(texto)
        
        # Verificar que tenga la clave 'escenas'
        if 'escenas' not in guion or not isinstance(guion['escenas'], list):
            print("⚠️ La IA no devolvió la clave 'escenas' correctamente. Usando respaldo.")
            raise ValueError("Falta clave 'escenas'")
            
        print("✅ Guion generado exitosamente.")
        return guion
        
    except Exception as e:
        print(f"⚠️ Error al procesar la IA ({e}). Usando guion de respaldo seguro.")
        return {
            "titulo": tema,
            "escenas": [
                {"fase": "Umbral", "narracion": "Explorando el Nodo Sur y la herida original.", "prompt_visual": "Dark atmospheric puppet theater, mysterious", "duracion_segundos": 10},
                {"fase": "Descenso", "narracion": "La mascara se agrieta, revelando el miedo interior.", "prompt_visual": "Cracked golden mask, dramatic shadows, emotional", "duracion_segundos": 10},
                {"fase": "Encuentro", "narracion": "El momento de la verdad: aceptar la vulnerabilidad.", "prompt_visual": "Puppet looking in mirror, warm light emerging", "duracion_segundos": 10},
                {"fase": "Sintesis", "narracion": "Transitando al Nodo Norte. El poder de la autenticidad.", "prompt_visual": "Puppet without mask, surrounded by light, hopeful", "duracion_segundos": 10}
            ]
        }

def generar_imagenes(prompts, output_dir):
    print("🖼️ Agente 2: Generando imagenes (Placeholder)...")
    imagenes = []
    for i, prompt in enumerate(prompts):
        img = Image.new('RGB', (1920, 1080), color=(15, 15, 25))
        draw = ImageDraw.Draw(img)
        try:
            font = ImageFont.truetype("arial.ttf", 40)
        except:
            font = ImageFont.load_default()
        draw.text((100, 100), "ESCENA " + str(i+1), fill=(255, 105, 180), font=font)
        draw.text((100, 200), prompt[:80], fill=(200, 200, 200), font=font)
        path = os.path.join(output_dir, "escena_" + str(i).zfill(2) + ".png")
        img.save(path)
        imagenes.append(path)
    return imagenes

async def generar_audio(narraciones, output_dir):
    print("🎙️ Agente 3: Sintetizando voz...")
    audios = []
    voz = "es-CL-LorenzoNeural"
    for i, texto in enumerate(narraciones):
        if not texto.strip():
            texto = "Silencio reflexivo."
        path = os.path.join(output_dir, "audio_" + str(i).zfill(2) + ".mp3")
        communicate = edge_tts.Communicate(texto, voz)
        await communicate.save(path)
        audios.append(path)
    return audios

def ensamblar_video(imagenes, audios, output_path):
    print("🎬 Agente 4: Ensamblando video...")
    if not imagenes or not audios:
        print("❌ Error: No hay imagenes o audios para ensamblar.")
        return None
        
    clips = []
    for img, aud in zip(imagenes, audios):
        audio_clip = AudioFileClip(aud)
        img_clip = ImageClip(img).with_duration(audio_clip.duration)
        img_clip = img_clip.with_audio(audio_clip)
        clips.append(img_clip)
    
    video_final = concatenate_videoclips(clips, method="compose")
    video_final.write_videofile(output_path, fps=24, codec='libx264', audio_codec='aac', logger=None)
    return output_path

async def main():
    tema = "La mascara del lider toxico"
    print("🎭 Iniciando produccion: " + tema + "\n")
    
    guion = generar_guion(tema)
    print("📌 Titulo: " + str(guion.get('titulo', tema)))
    
    escenas = guion.get('escenas', [])
    if not escenas:
        print("❌ No se encontraron escenas en el guion. Abortando.")
        return

    prompts = [e.get('prompt_visual', 'Abstract') for e in escenas]
    narraciones = [e.get('narracion', '...') for e in escenas]
    
    imagenes = generar_imagenes(prompts, OUTPUT_DIR)
    audios = await generar_audio(narraciones, OUTPUT_DIR)
    
    nombre_archivo = tema.replace(" ", "_").replace(":", "") + ".mp4"
    path_final = os.path.join(OUTPUT_DIR, nombre_archivo)
    
    resultado = ensamblar_video(imagenes, audios, path_final)
    if resultado:
        print("\n🎉 ¡ÉXITO! Video guardado en: " + os.path.abspath(resultado))
    else:
        print("\n❌ Fallo al generar el video.")

if __name__ == "__main__":
    asyncio.run(main())