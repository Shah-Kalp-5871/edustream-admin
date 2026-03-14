<!DOCTYPE html>
<html lang="gu">
<head>
    <meta charset="UTF-8">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/gsap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/ScrollTrigger.min.js"></script>
    <style>
        body { background: #000; color: #fff; overflow-x: hidden; margin: 0; font-family: 'Segoe UI', sans-serif; }
        .viewport { position: fixed; inset: 0; perspective: 800px; overflow: hidden; }
        .scene { position: absolute; width: 100%; height: 100%; transform-style: preserve-3d; }
        
        .floating-node {
            position: absolute; top: 50%; left: 50%;
            transform-style: preserve-3d;
            font-weight: 900; color: rgba(255, 255, 255, 0.12);
            pointer-events: none;
            white-space: nowrap;
            text-shadow: 0 0 10px rgba(255,255,255,0.1);
        }

        .center-content {
            position: fixed; top: 50%; left: 50%;
            transform: translate(-50%, -50%);
            text-align: center; z-index: 10;
        }

        .timer-glow {
            font-size: clamp(3rem, 12vw, 10rem);
            font-weight: 900;
            background: linear-gradient(45deg, #fff, #ff8c00);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            filter: drop-shadow(0 0 40px rgba(255, 140, 0, 0.6));
            font-variant-numeric: tabular-nums;
        }
    </style>
</head>
<body>

    <div class="viewport">
        <div class="scene" id="vortex"></div>

        <div class="center-content">
            <h2 class="text-orange-500 tracking-[1.2em] text-xs font-bold mb-4 uppercase">Gujju Scholar</h2>
            <div id="timer" class="timer-glow">00:00:00:00</div>
            <p class="text-gray-500 tracking-[0.5em] mt-6 uppercase text-[10px] font-bold">Evolution starts April 2, 2026</p>
        </div>
    </div>

    <div style="height: 800vh;"></div>

    <script>
        gsap.registerPlugin(ScrollTrigger);

        const vortex = document.getElementById('vortex');
        const items = ["ગણિત", "વિજ્ઞાન", "ગુજરાતી", "સામાજિક વિજ્ઞાન", "અંગ્રેજી", "હિન્દી", "સંસ્કૃત", "પર્યાવરણ", "કમ્પ્યુટર"];
        const count = 180; // More items to fill the wider space

        for (let i = 0; i < count; i++) {
            const node = document.createElement('div');
            node.className = 'floating-node';
            node.innerText = items[i % items.length];
            
            // WIDE VORTEX MATH
            const angle = i * 0.35; 
            // Radius now grows much faster (i * 20) to push back items to the edges
            const radius = 250 + (i * 20); 
            const x = Math.cos(angle) * radius;
            const y = Math.sin(angle) * radius;
            const z = -i * 250; // Deeper Z-spacing

            node.style.fontSize = (Math.random() * 2 + 1) + "rem";
            node.style.transform = `translate3d(${x}px, ${y}px, ${z}px)`;
            vortex.appendChild(node);
        }

        // Deep Descent Animation
        gsap.to(vortex, {
            z: 45000, // Pull further for a longer journey
            rotationZ: 1080, // Three full rotations for a dynamic feel
            ease: "none",
            scrollTrigger: {
                trigger: "body",
                start: "top top",
                end: "bottom bottom",
                scrub: 1.5
            }
        });

        // Interactive Perspective Tilt
        window.addEventListener("mousemove", (e) => {
            const rx = (e.clientY / window.innerHeight - 0.5) * 10;
            const ry = (e.clientX / window.innerWidth - 0.5) * 10;
            gsap.to(vortex, { rotationX: -rx, rotationY: ry, duration: 2 });
        });

        // Timer Logic
        const target = new Date("April 2, 2026 00:00:00").getTime();
        setInterval(() => {
            const now = new Date().getTime();
            const d = target - now;
            const days = String(Math.floor(d / 86400000)).padStart(2, '0');
            const hrs = String(Math.floor((d % 86400000) / 3600000)).padStart(2, '0');
            const min = String(Math.floor((d % 3600000) / 60000)).padStart(2, '0');
            const sec = String(Math.floor((d % 60000) / 1000)).padStart(2, '0');
            document.getElementById('timer').innerText = `${days}:${hrs}:${min}:${sec}`;
        }, 1000);
    </script>
</body>
</html>