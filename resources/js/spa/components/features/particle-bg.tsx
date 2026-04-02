import { useEffect, useRef } from "react";

interface Dot {
  x: number; y: number; vx: number; vy: number; r: number;
}

export function ParticleBg({ className = "" }: { className?: string }) {
  const canvas = useRef<HTMLCanvasElement>(null);

  useEffect(() => {
    const el = canvas.current;
    if (!el) return;
    const ctx = el.getContext("2d")!;
    let raf: number;
    let dots: Dot[] = [];

    const resize = () => {
      el.width = el.offsetWidth * 2;
      el.height = el.offsetHeight * 2;
      ctx.scale(2, 2);
    };

    const init = () => {
      resize();
      const count = Math.floor((el.offsetWidth * el.offsetHeight) / 12000);
      dots = Array.from({ length: Math.min(count, 80) }, () => ({
        x: Math.random() * el.offsetWidth,
        y: Math.random() * el.offsetHeight,
        vx: (Math.random() - 0.5) * 0.3,
        vy: (Math.random() - 0.5) * 0.3,
        r: Math.random() * 1.5 + 0.5,
      }));
    };

    const draw = () => {
      const w = el.offsetWidth;
      const h = el.offsetHeight;
      ctx.clearRect(0, 0, w, h);

      // Move dots
      dots.forEach((d) => {
        d.x += d.vx;
        d.y += d.vy;
        if (d.x < 0 || d.x > w) d.vx *= -1;
        if (d.y < 0 || d.y > h) d.vy *= -1;
      });

      // Draw connections
      const maxDist = 120;
      for (let i = 0; i < dots.length; i++) {
        for (let j = i + 1; j < dots.length; j++) {
          const dx = dots[i].x - dots[j].x;
          const dy = dots[i].y - dots[j].y;
          const dist = Math.sqrt(dx * dx + dy * dy);
          if (dist < maxDist) {
            const alpha = (1 - dist / maxDist) * 0.15;
            ctx.strokeStyle = `rgba(21, 94, 239, ${alpha})`;
            ctx.lineWidth = 0.5;
            ctx.beginPath();
            ctx.moveTo(dots[i].x, dots[i].y);
            ctx.lineTo(dots[j].x, dots[j].y);
            ctx.stroke();
          }
        }
      }

      // Draw dots
      dots.forEach((d) => {
        ctx.fillStyle = "rgba(21, 94, 239, 0.25)";
        ctx.beginPath();
        ctx.arc(d.x, d.y, d.r, 0, Math.PI * 2);
        ctx.fill();
      });

      raf = requestAnimationFrame(draw);
    };

    init();
    draw();
    window.addEventListener("resize", init);
    return () => { cancelAnimationFrame(raf); window.removeEventListener("resize", init); };
  }, []);

  return <canvas ref={canvas} className={`absolute inset-0 w-full h-full pointer-events-none ${className}`} />;
}
