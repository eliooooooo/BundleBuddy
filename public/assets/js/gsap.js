import { gsap } from "gsap";

window.addEventListener('load', function() {
    gsap.from(".letter", {
        duration: 0.5,
        scale: 0,
        y: 80,
        rotation: 180,
        ease: "back.out(1.7)",
        stagger: {
            from: "random",
            each: 0.1
        }
    });
});