import { spawn } from 'child_process';

export const prerender = false;

export async function POST() {
    return new Promise((resolve) => {
        const cmd = 'git add . && git commit -m "Backup_automatico" && git push origin main';
        const child = spawn('cmd.exe', ['/c', cmd]);

        let output = '';
        child.stdout.on('data', (data) => output += data.toString());
        child.stderr.on('data', (data) => output += data.toString());

        child.on('close', (code) => {
            if (code === 0) {
                resolve(new Response(JSON.stringify({ success: true, log: "Sincronización exitosa" }), { status: 200 }));
            } else {
                resolve(new Response(JSON.stringify({ success: false, error: output }), { status: 500 }));
            }
        });
    });
}