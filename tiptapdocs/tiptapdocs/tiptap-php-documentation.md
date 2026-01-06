# TipTap PHP/Laravel Documentation

This documentation was scraped from [tiptap.dev](https://tiptap.dev)

---

## PHP | Tiptap Editor Docs

**Source:** [https://tiptap.dev/docs/editor/getting-started/install/php](https://tiptap.dev/docs/editor/getting-started/install/php)

---

You can use Tiptap with Laravel, Livewire, Inertia.js, Alpine.js, Tailwind CSS, and even—yes, you read that right—inside PHP.
We provide
an official PHP package to work with Tiptap content
. You can transform Tiptap-compatible JSON to HTML and vice versa, sanitize your content, or just modify it.
Laravel Livewire
my-livewire-component.blade.php
<!--
In your livewire component you could add an
autosave method to handle saving the content
from the editor every 10 seconds if you wanted
-->
<
x-editor
wire:model
=
"foo"
wire:poll.10000ms
=
"autosave"
></
x-editor
>
Hint
The
.defer
modifier is no longer available in Livewire v3, as updating the state is deferred by default. Use the
.live
modifier if you need to update the state server-side, as it changes.
editor.blade.php
<
div
x-data
=
"setupEditor(
$wire.entangle('{{ $attributes->wire('model')->value() }}').defer
)"
x-init
=
"() => init($refs.editor)"
wire:ignore
{{
$attributes-
>whereDoesntStartWith('wire:model') }}
>
<
div
x-ref
=
"editor"
></
div
>
</
div
>
index.js
import
{ Editor }
from
'@tiptap/core'
import
StarterKit
from
'@tiptap/starter-kit'
window.
setupEditor
=
function
(
content
) {
let
editor
return
{
content: content,
init
(
element
) {
editor
=
new
Editor
({
element: element,
extensions: [StarterKit],
content:
this
.content,
onUpdate
: ({
editor
})
=>
{
this
.content
=
editor.
getHTML
()
},
})
this
.
$watch
(
'content'
, (
content
)
=>
{
// If the new content matches Tiptap's then we just skip.
if
(content
===
editor.
getHTML
())
return
/*
Otherwise, it means that an external source
is modifying the data on this Alpine component,
which could be Livewire itself.
In this case, we only need to update Tiptap's
content and we're done.
For more information on the `setContent()` method, see:
https://www.tiptap.dev/api/commands/set-content
*/
editor.commands.
setContent
(content,
false
)
})
},
}
}
Next steps
Configure your editor
Add styles to your editor
Learn more about Tiptaps concepts
Learn how to persist the editor state
Start building your own extensions
Previously
Alpine
Next up
CDN

---

## Install the Editor | Tiptap Editor Docs

**Source:** [https://tiptap.dev/docs/editor/getting-started/install](https://tiptap.dev/docs/editor/getting-started/install)

---

Tiptap is framework-agnostic and even works with vanilla JavaScript (if that's your thing). Use the following guides to integrate Tiptap into your JavaScript project.
JavaScript
React
Next
Vue 3
Vue 2
Nuxt
Svelte
Alpine.js
PHP
CDN
Community efforts
Angular
Solid
Previously
Overview
Next up
Vanilla JavaScript

---

## Configuration | Tiptap Editor Docs

**Source:** [https://tiptap.dev/docs/editor/getting-started/configure](https://tiptap.dev/docs/editor/getting-started/configure)

---

To configure Tiptap, specify three key elements:
where it should be rendered (
element
)
Note
: This is not required if you use the React or Vue integrations.
which functionalities to enable (
extensions
)
what the initial document should contain (
content
)
While this setup works for most cases, you can configure additional options.
Add your configuration
To configure the editor, pass
an object with settings
to the
Editor
class, as shown below:
import
{ Editor }
from
'@tiptap/core'
import
Document
from
'@tiptap/extension-document'
import
Paragraph
from
'@tiptap/extension-paragraph'
import
Text
from
'@tiptap/extension-text'
new
Editor
({
// bind Tiptap to the `.element`
element: document.
querySelector
(
'.element'
),
// register extensions
extensions: [Document, Paragraph, Text],
// set the initial content
content:
'<p>Example Text</p>'
,
// place the cursor in the editor after initialization
autofocus:
true
,
// make the text editable (default is true)
editable:
true
,
// prevent loading the default ProseMirror CSS that comes with Tiptap
// should be kept as `true` for most cases as it includes styles
// important for Tiptap to work correctly
injectCSS:
false
,
})
Nodes, marks, and extensions
Most editing features are packaged as
nodes
,
marks
, or
functionality
. Import what you need and pass them as an array to the editor.
Here's the minimal setup with only three extensions:
import
{ Editor }
from
'@tiptap/core'
import
Document
from
'@tiptap/extension-document'
import
Paragraph
from
'@tiptap/extension-paragraph'
import
Text
from
'@tiptap/extension-text'
new
Editor
({
element: document.
querySelector
(
'.element'
),
extensions: [Document, Paragraph, Text],
})
Configure extensions
Many extensions can be configured with the
.configure()
method. You can pass an object with specific settings.
For example, to limit the heading levels to 1, 2, and 3, configure the
Heading
extension as shown below:
import
{ Editor }
from
'@tiptap/core'
import
Document
from
'@tiptap/extension-document'
import
Paragraph
from
'@tiptap/extension-paragraph'
import
Text
from
'@tiptap/extension-text'
import
Heading
from
'@tiptap/extension-heading'
new
Editor
({
element: document.
querySelector
(
'.element'
),
extensions: [
Document,
Paragraph,
Text,
Heading.
configure
({
levels: [
1
,
2
,
3
],
}),
],
})
Refer to the extension's documentation for available settings.
A bundle with the most common extensions
We have bundled a few of the most common extensions into the
StarterKit
. Here's how to use it:
import
StarterKit
from
'@tiptap/starter-kit'
new
Editor
({
extensions: [StarterKit],
})
You can configure all extensions included in the
StarterKit
by passing an object. To target specific extensions, prefix their configuration with the name of the extension. For example, to limit heading levels to 1, 2, and 3:
import
StarterKit
from
'@tiptap/starter-kit'
new
Editor
({
extensions: [
StarterKit.
configure
({
heading: {
levels: [
1
,
2
,
3
],
},
}),
],
})
Disable specific StarterKit extensions
To exclude certain extensions
StarterKit
, you can set them to
false
in the configuration. For example, to disable the
Undo/Redo History
extension:
import
StarterKit
from
'@tiptap/starter-kit'
new
Editor
({
extensions: [
StarterKit.
configure
({
undoRedo:
false
,
}),
],
})
When using Tiptap's
Collaboration
, which comes with its own history extension, you must disable the
Undo/Redo History
extension included in the
StarterKit
to avoid conflicts.
Additional extensions
The
StarterKit
doesn't include all available extensions. To add more features to your editor, list them in the
extensions
array. For example, to add the
Strike
extension:
import
StarterKit
from
'@tiptap/starter-kit'
import
Strike
from
'@tiptap/extension-strike'
new
Editor
({
extensions: [StarterKit, Strike],
})
Next steps
Add styles to your editor
Learn more about Tiptaps concepts
Learn how to persist the editor state
Start building your own extensions
Previously
CDN
Next up
Styling

---

## Alpine | Tiptap Editor Docs

**Source:** [https://tiptap.dev/docs/editor/getting-started/install/alpine](https://tiptap.dev/docs/editor/getting-started/install/alpine)

---

The following guide describes how to integrate Tiptap with version 3 of Alpine.js. For the sake of this guide, we'll use Vite to quickly set up a project, but you can use whatever you're used to. Vite is just really fast and we love it!
Requirements
Node
installed on your machine
Experience with
Alpine.js
Create a project (optional)
If you already have an Alpine.js project, that's fine too. Just skip this step.
For the purpose of this guide, start with a fresh
Vite
project called
my-tiptap-project
. Vite sets up everything we need, just select the Vanilla JavaScript template.
npm
init
vite@latest
my-tiptap-project
--
--template
vanilla
cd
my-tiptap-project
npm
install
npm
run
dev
Install the dependencies
Okay, enough of the boring boilerplate work. Let's finally install Tiptap! For the following example, you'll need
alpinejs
, the
@tiptap/core
package, the
@tiptap/pm
package, and the
@tiptap/starter-kit
, which includes the most common extensions to get started quickly.
npm
install
alpinejs
@tiptap/core
@tiptap/pm
@tiptap/starter-kit
If you followed step 1, you can now start your project with
npm run dev
, and open
http://localhost:5173
in your favorite browser. This might be different if you're working with an existing project.
Integrate Tiptap
To actually start using Tiptap, you'll need to write a little bit of JavaScript. Let's put the following example code in a file called
main.js
.
This is the fastest way to get Tiptap up and running with Alpine.js. It will give you a very basic version of Tiptap. No worries, you will be able to add more functionality soon.
import
Alpine
from
'alpinejs'
import
{ Editor }
from
'@tiptap/core'
import
StarterKit
from
'@tiptap/starter-kit'
document.
addEventListener
(
'alpine:init'
, ()
=>
{
Alpine.
data
(
'editor'
, (
content
)
=>
{
let
editor
// Alpine's reactive engine automatically wraps component properties in proxy objects. If you attempt to use a proxied editor instance to apply a transaction, it will cause a "Range Error: Applying a mismatched transaction", so be sure to unwrap it using Alpine.raw(), or simply avoid storing your editor as a component property, as shown in this example.
return
{
updatedAt: Date.
now
(),
// force Alpine to rerender on selection change
init
() {
const
_this
=
this
editor
=
new
Editor
({
element:
this
.$refs.element,
extensions: [StarterKit],
content: content,
onCreate
({
editor
}) {
_this.updatedAt
=
Date.
now
()
},
onUpdate
({
editor
}) {
_this.updatedAt
=
Date.
now
()
},
onSelectionUpdate
({
editor
}) {
_this.updatedAt
=
Date.
now
()
},
})
},
isLoaded
() {
return
editor
},
isActive
(
type
,
opts
=
{}) {
return
editor.
isActive
(type, opts)
},
toggleHeading
(
opts
) {
editor.
chain
().
toggleHeading
(opts).
focus
().
run
()
},
toggleBold
() {
editor.
chain
().
focus
().
toggleBold
().
run
()
},
toggleItalic
() {
editor.
chain
().
toggleItalic
().
focus
().
run
()
},
}
})
})
window.Alpine
=
Alpine
Alpine.
start
()
Add it to your app
Now, let's replace the contents of
index.html
with the following example code to use the editor in our app.
<!
doctype
html
>
<
html
lang
=
"en"
>
<
head
>
<
meta
charset
=
"UTF-8"
/>
</
head
>
<
body
>
<
div
x-data
=
"editor('
<
p>Hello world! :-)
<
/p>')"
>
<
template
x-if
=
"isLoaded()"
>
<
div
class
=
"menu"
>
<
button
@click
=
"toggleHeading({ level: 1 })"
:class
=
"{ 'is-active': isActive('heading', { level: 1 }, updatedAt) }"
>
H1
</
button
>
<
button
@click
=
"toggleBold()"
:class
=
"{ 'is-active' : isActive('bold', updatedAt) }"
>
Bold
</
button
>
<
button
@click
=
"toggleItalic()"
:class
=
"{ 'is-active' : isActive('italic', updatedAt) }"
>
Italic
</
button
>
</
div
>
</
template
>
<
div
x-ref
=
"element"
></
div
>
</
div
>
<
script
type
=
"module"
src
=
"/main.js"
></
script
>
<
style
>
body
{
margin
:
2
rem
;
font-family
:
sans-serif
;
}
button
.is-active
{
background
:
black
;
color
:
white
;
}
.tiptap
{
padding
:
0.5
rem
1
rem
;
margin
:
1
rem
0
;
border
:
1
px
solid
#ccc
;
}
</
style
>
</
body
>
</
html
>
Tiptap should now be visible in your browser. Time to give yourself a pat on the back! :)
Next steps
Configure your editor
Add styles to your editor
Learn more about Tiptaps concepts
Learn how to persist the editor state
Start building your own extensions
Previously
Svelte
Next up
PHP

---

## CDN | Tiptap Editor Docs

**Source:** [https://tiptap.dev/docs/editor/getting-started/install/cdn](https://tiptap.dev/docs/editor/getting-started/install/cdn)

---

For testing purposes or demos, use our esm.sh CDN builds. Here are a few lines of code you need to get started.
<!
doctype
html
>
<
html
>
<
head
>
<
meta
charset
=
"utf-8"
/>
</
head
>
<
body
>
<
div
class
=
"element"
></
div
>
<
script
type
=
"module"
>
import
{ Editor }
from
'https://esm.sh/@tiptap/core'
import
StarterKit
from
'https://esm.sh/@tiptap/starter-kit'
const
editor
=
new
Editor
({
element: document.
querySelector
(
'.element'
),
extensions: [StarterKit],
content:
'<p>Hello World!</p>'
,
})
</
script
>
</
body
>
</
html
>
Tiptap should now be visible in your browser. Time to give yourself a pat on the back! :)
Next steps
Configure your editor
Add styles to your editor
Learn more about Tiptaps concepts
Learn how to persist the editor state
Start building your own extensions
Previously
PHP
Next up
Configure

---

