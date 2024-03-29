---
title: Declarative and Imperative design
permalink: "posts/{{ title | slug }}/index.html"
date: 2022-05-06
---

I recently read [declarative design](https://adactio.com/journal/18982) by
Jeremy Keith.  In the post he discusses different approaches to writing CSS in
terms of declarative or imperative design where *declarative* is used to
describe CSS authoring techniques that leverage the power of CSS and the cascade
such as [Utopia](https://utopia.fyi/) [Every Layout](https://every-layout.dev/) and intrinsic design and *imperative* for
tools such as [Tailwind](https://tailwindcss.com/).

<!--more-->

I found his framing is a refreshing break from the usual mud-slinging arguments
around this topic "CSS is terrible!", "Tailwind!? Might as well use inline styles!":

> I’m not saying that declarative tools—like Utopia—are right and that imperative
> tools—like Tailwind—are wrong. As always, it depends. In this case, it depends
> on the mindset you have...

> ...I wonder whether your path into web design and development might also factor
> into which end of the spectrum you’d identify with. Like, if your background
> is in declarative languages like HTML and CSS, maybe intrisic web design
> really resonates. But if your background is in imperative languages like
> JavaScript, perhaps Tailwind makes more sense to you.

My background in web development is definitely HTML and CSS, and my preference
is definitely towards the "CSS is awesome" end of the spectrum. I love the power
and flexibility that comes from using *intrinsic design* techniques such as
those in Every Layout.  The ability to build responsive sites that don't need
media queries, and just adapt to the viewport size is incredible.  

Having said that I've also used Tailwind, going so far as to choose it as the
default option at my previous agency.  In that context I found the consistency,
and simplicity<a href="#footnote-1"><sup>1</sup></a> of Tailwind was a great
benefit – the fact that the same styles and classes would be available across
all of our projects made context switching or going back to prior projects so
much easier. However I definitely missed some of the flexibilty of the intrinsic
approach, so much so I made [Tailwind Utopia](https://github.com/cwsdigital/tailwind-utopia) a tailwind
plugin for using a Utopia fluid type scale.

I definitely agree with Jeremy that the underlying philosophy you bring to
these tools will influence your experience of using them.  While I believe
Tailwind was the right choice in the agency, and being able to trhow classes in
the HTML was fast, as soon as you got beyond the basics it often felt a little
bit like *going against the grain*.

<p id="footnote-1">
<small>
    <sup>1</sup> Many would disagree with calling Tailwind simple! I found
    that techniques such as those in Every Layout while powerful, require an
    in-depth knowledge of the intricacies of CSS that made it harder for the
    whole team to understand and edit the styles with confidence. In comparison,
    Tailwind was easier to understand, and had excellent up-to-date
    documentation.
    </small>
</p>




