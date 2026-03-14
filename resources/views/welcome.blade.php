<!DOCTYPE html>
<html lang="gu">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gujju Scholar | Immersive Education</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/gsap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/ScrollTrigger.min.js"></script>

    <style>
        :root { --accent: #ff8c00; }
        body { margin: 0; background: #010409; color: white; overflow-x: hidden; }
        
        .viewport {
            position: fixed; top: 0; left: 0; width: 100%; height: 100vh;
            perspective: 600px; /* Further reduced for a massive wide-angle feel */
            display: flex; align-items: center; justify-content: center;
            overflow: hidden;
            background: radial-gradient(circle at center, #0f172a 0%, #010409 100%);
        }

        .world {
            position: absolute; width: 100%; height: 100%;
            transform-style: preserve-3d;
            will-change: transform;
        }

        /* The Data Streaks (Fiber Optics) */
        .streak {
            position: absolute;
            width: 2px; height: 600px;
            background: linear-gradient(to bottom, transparent, var(--accent), transparent);
            opacity: 0.2;
        }

        /* The Fragments - Subject Names */
        .fragment {
            position: absolute; font-weight: 900;
            pointer-events: none; white-space: nowrap;
            text-shadow: 0 0 15px rgba(255,255,255,0.1);
            font-family: 'Segoe UI', sans-serif;
        }

        /* The Master Timer */
        .hero-timer {
            position: absolute; text-align: center;
            transform-style: preserve-3d;
            z-index: 1000;
        }

        .timer-val {
            font-size: clamp(3rem, 18vw, 15rem);
            font-weight: 900; letter-spacing: -0.05em;
            background: linear-gradient(to bottom, #fff 30%, var(--accent));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            filter: drop-shadow(0 0 50px rgba(255, 140, 0, 0.4));
            font-variant-numeric: tabular-nums;
        }

        /* Fog Overlay */
        .fog {
            position: fixed; top: 0; left: 0; width: 100%; height: 100%;
            background: radial-gradient(circle at center, transparent 10%, #010409 95%);
            pointer-events: none; z-index: 10;
        }
    </style>
</head>
<body>

    <div class="viewport">
        <div class="fog"></div>
        <div class="world" id="world">
            
            <div class="hero-timer" style="transform: translateZ(-12000px);">
                <div id="status" class="text-orange-500 tracking-[1.5em] font-black mb-2 opacity-60">શિક્ષણ જ શક્તિ છે</div>
                <div class="timer-val" id="timer">00:00:00:00</div>
                <div class="text-2xl font-light tracking-[0.5em] text-white/40 mt-4 uppercase">Gujju Scholar Launch</div>
            </div>

        </div>
    </div>

    <div style="height: 1200vh;"></div>

    <script>
        gsap.registerPlugin(ScrollTrigger);
        const world = document.getElementById('world');

        // REPLACED: Std 1 to 10 Gujarati Subjects
        const subjects = [
            "ગણિત", "વિજ્ઞાન", "ગુજરાતી", "સામાજિક વિજ્ઞાન", 
            "અંગ્રેજી", "હિન્દી", "સંસ્કૃત", "પર્યાવરણ", 
            "કમ્પ્યુટર", "ચિત્રકામ", "પી.ટી.", "હિસાબી પદ્ધતિ"
        ];
        
        // 1. Generate Ultra-Wide Hyper-Depth Environment
        const count = 250; // Increased for higher density
        
        for(let i=0; i<count; i++) {
            const f = document.createElement('div');
            f.className = 'fragment';
            f.innerText = subjects[Math.floor(Math.random() * subjects.length)];
            
            // Random scaling for natural depth feel
            f.style.fontSize = (Math.random() * 5 + 1.5) + "rem";
            f.style.opacity = Math.random() * 0.4 + 0.1;
            
            // WIDER DISTRIBUTION MATH
            // Using larger multipliers for X and Y to fill the "wide" vortex
            let x = (Math.random() - 0.5) * 8000; 
            let y = (Math.random() - 0.5) * 6000;
            let z = -Math.random() * 15000; // Deep Z-range
            
            f.style.transform = `translate3d(${x}px, ${y}px, ${z}px)`;
            world.appendChild(f);

            // Fiber Optic Streaks
            if(i % 4 === 0) {
                const s = document.createElement('div');
                s.className = 'streak';
                // Position streaks near fragments for a "connected" look
                s.style.transform = `translate3d(${x + (Math.random()*200)}, ${y}, ${z}) rotateX(90deg)`;
                world.appendChild(s);
            }
        }

        // 2. The Grand Descent (Scroll Animation)
        gsap.to(world, {
            z: 14000, // Pulls the world far past the viewer
            ease: "none",
            scrollTrigger: {
                trigger: "body",
                start: "top top",
                end: "bottom bottom",
                scrub: 1.5
            }
        });

        // 3. Dynamic Background Lighting Shift
        gsap.to(".viewport", {
            background: "radial-gradient(circle at center, #351c02 0%, #010409 100%)",
            scrollTrigger: {
                trigger: "body",
                start: "75% center",
                end: "bottom bottom",
                scrub: true
            }
        });

        // 4. Interactive Lens Tilt
        window.addEventListener('mousemove', (e) => {
            const x = (e.clientX / window.innerWidth - 0.5) * 12;
            const y = (e.clientY / window.innerHeight - 0.5) * 12;
            gsap.to(world, { rotationY: x, rotationX: -y, duration: 2, ease: "power2.out" });
        });

        // 5. Precise Countdown (April 2, 2026)
        const targetDate = new Date("April 2, 2026 00:00:00").getTime();
        function update() {
            const now = new Date().getTime();
            const diff = targetDate - now;
            
            if (diff <= 0) {
                document.getElementById('timer').innerText = "00:00:00:00";
                return;
            }

            const d = String(Math.floor(diff / 86400000)).padStart(2, '0');
            const h = String(Math.floor((diff % 86400000) / 3600000)).padStart(2, '0');
            const m = String(Math.floor((diff % 3600000) / 60000)).padStart(2, '0');
            const s = String(Math.floor((diff % 60000) / 1000)).padStart(2, '0');
            document.getElementById('timer').innerText = `${d}:${h}:${m}:${s}`;
        }
        setInterval(update, 1000); update();
    </script>
</body>
</html>