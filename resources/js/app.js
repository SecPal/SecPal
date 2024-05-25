import './bootstrap';

import ui from '@alpinejs/ui'
import focus from '@alpinejs/focus'

Alpine.plugin(ui)
Alpine.plugin(focus)

import Quill from 'quill';
import 'quill/dist/quill.snow.css';

window.Quill = Quill;