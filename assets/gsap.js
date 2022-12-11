import { gsap } from "gsap";

const tl = gsap.timeline({
    repeat: -1, 
    repeatDelay: 1 + Math.random() * 4
});

tl.to('.glitch', {
    duration:0.01, 
    skewX:70,
    ease: "power4.easeInOut"
})
.to('.glitch', {
    duration: 0.04,
    skewX:0,
    ease: "power4.easeInOut"
})
.to('.glitch', {
    duration: 0.01,
    opacity:0
})
.to('.glitch', {
    duration: 0.01,
    opacity:1
})
.to('.glitch', {
    duration: 0.01,
    x:-10,
    ease: "rough({ template: none.out, strength: 1, points: 20, taper: none, randomize: true, clamp: false})"
})
.to('.glitch', {
    duration: 0.01,
    x:0
})
.to('.top', {
    duration: 0.01,
    opacity: 0,
    x:-30,
    ease: "rough({ template: none.out, strength: 1, points: 20, taper: none, randomize: true, clamp: false})"
})
.to('.bottom', {
    duration: 0.01,
    x:20,
    ease: "rough({ template: none.out, strength: 1, points: 20, taper: none, randomize: true, clamp: false})"
})
.to('#txt', {
    duration: 0.01, 
    scale: Math.random() * 2
})
.to('#txt', {
    duration: 0.01,
    scale: 1,
    ease: "rough({ template: none.out, strength: 1, points: 20, taper: none, randomize: true, clamp: false})"
}, "+=0.02")

.to('.top', {
    duration: 0.05,
    x:0,
    ease: "rough({ template: none.out, strength: 1, points: 20, taper: none, randomize: true, clamp: false})"
})
.to('.bottom', {
    duration: 0.05,
    x:0,
    ease: "rough({ template: none.out, strength: 1, points: 20, taper: none, randomize: true, clamp: false})"
})

.to('.glitch', {
    duration: 0.02,
    scaleY: Math.random() * 2,
    ease: "Power4.easeInOut"
})
.to('.glitch', {
    duration: 0.04,
    scaleY:1,
    ease: "Power4.easeInOut"
})