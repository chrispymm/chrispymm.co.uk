---
title: Fixing 301 Redirects in Chrome
permalink: posts/fixing-301-redirects-in-chrome/
date: 2020-01-25
tags: post
---

I came across an issue this week where I accidentally added a 301 redirect to a page on a site I was working on. The issues was that Chrome aggressively caches 301 redirects, and as this had been added in error I needed to find a way to 'undo' the redirect.  It took me quite a while to find the solution to this, but in the end it was deceptively simple. 

<!--more-->

When searching for solutions for how to clear Chrome's DNS cache, there are no end of results for how to clear it on your own computer.  However none of these are practical for users coming back to the website who have the redirect cached.

I assumed that I couldn't simply put in place a redirect reversing the error as that would cause a redirect loop - so the user would end up seeing a confusiong error page.  

After much searching, to no avail I had come to the conclusion that I would just have to put in place the redirect and live with it.  The error page did at least sort-of explain what had happened and how the user could fix it.  It would be a pretty terrible user experience, but it didn't seem like there was any other option.

Then I came across a comment buried deep in some Stack-Overflow comments that seemed to suggest that Chrome was clever enough to figure things out if you puposefully created the redirect loop.

So, I figured I had nothing else to lose. With no other options I decided to setup the redirect. It worked! Setting up the loop forces Chrome to revalidate the 301 redirect it has cached - on finding it no longer exists, it corrcetly loads the page.  There is a brief flash of the 'Too many Redirects' error screen, but then the site loads correctly.

So, should you ever need to 'undo' a 301 redirect, all you need to do the un-intuitive thing and setup the reverse redirect. 



