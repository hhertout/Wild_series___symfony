/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you import will output into a single css file (app.css in this case)
import './styles/app.scss';
import './styles/home.scss';
import './styles/nav.scss';

// start the Stimulus application
import './bootstrap';

const $ = require('jquery');
// this "modifies" the jquery module: adding behavior to it
// the bootstrap module doesn't export/return anything
require('bootstrap');

// or you can include specific pieces
// require('bootstrap/js/dist/tooltip');
// require('bootstrap/js/dist/popover');

import { gsap } from "gsap";

const tl = gsap.timeline({
    repeat: -1, 
    repeatDelay: Math.random() * 7
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
    duration: 0.02,
    x:0,
    ease: "rough({ template: none.out, strength: 1, points: 20, taper: none, randomize: true, clamp: false})"
})
.to('.bottom', {
    duration: 0.02,
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



