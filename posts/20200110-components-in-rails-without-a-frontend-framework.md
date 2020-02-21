---
title: Refactoring a Rails App to use Components
permalink: "posts/{{ title | slug }}/index.html"
date: 2020-01-10
---

Early last year we wanted to overhaul the user interface of our app.  I was keen to improve the structure of the app and ensure that the code became more readable and maintainable - at that point the app was about 6 years old and some of the view code had become overly complex and hard to follow.

<!--more-->

What I wanted was all the benifits of a reusable component library, without having to rebuild the whole UI using a javascript framework.  The solution I found was [komponent](https://github.com/komposable/komponent).

Komponent is a rails gem that provides a way of structuring and managing your front-end code in a component-based manner.  It uses Rails view_contexts and webpack configuration to allow you to create rails view components. 

Each component is a self-contained directory consisting of a view file (using your template language of choice), a ruby module, a css file, a javascript file and an optional stimulus controller.  All of yoiur components are stored by default in a `/frontend` directory in the root of your application.  So you end up with a directory structure somehting like below:

```shell
app/
frontend/
  components/
    └──button/
        ├──_button.html.erb
        ├──_examples.html.erb (optional)
        ├──button.js 
        ├──button_component.rb    
        └──button_controller.js (optional)
    ├── notice/    
    └── pill/ 
```

Components can have blocks passed to them, can be nested, and the ruby module can be used to define properties of the component, giving them default values or making them required as necessary.  You can define methods as required within the module to have computed properties, or to extract logic out of the view partial.

There's no enforced namespacing of the css, but it is trivial to implement, keeping all the styles for the component self-contained.

We chose to pair this setup with [Tailwind](https://tailwindcss.com) a utility-first css libary, which works excellently in this component based setup, meaning each component has very minimal styles defined in it's stylesheet.

Using your component in a rails view is as easy as using the `component` helper method.

```erb
  <%= c("button", label: "My Button") %>
```

Oh, and did you spot that `_examples.html.erb` file in the directory tree above?  Komponent can also generate a styleguide for you.  So you can provide examples and usage docuemntation alongside your components.

Taken all together komponent is a great way to organise and structure your rails front-end code.  It provides all the benefits of components, while keeping all the rails conventions you're used to.

If you're interested in implementing this into your app, check out my follow up posts:
* [Creating a Rails Component with Komponent Part 1](/posts/creating-rails-component-with-komponent-part-1/)
* [Creating a Rails Component with Komponent Part 2](/posts/creating-rails-component-with-komponent-part-2/)



### Further Reading
* [Modern Front End in Rails](https://evilmartians.com/chronicles/evil-front-part-1) - A series of articles building out a setup similar to komponent from scratch.  Great for understandoing what's going on behind the scenes.
* [How we develop design components in rails](https://betterment.engineering/how-we-develop-design-components-in-rails-ab2d3dac44d3) - A post from Betterment engineering on a similar solution they built.

A while ater I had started imlpementing this in our app I watched [this talk from RailsConf 2019](https://www.youtube.com/watch?v=y5Z5a6QdA-M&list=PLE7tQUdRKcyaOq3HlRm9h_Q_WhWKqm5xc&index=5&t=0s) where Joel Hawksley from Github, demos a very similar Component based system by creating an `ActionView::Component` class. This is apparently being pushed upstream, so could get incorpoarted into Rails, which would be great.  It doesn't address the structuring of files, and co-locating css and js files along with the component in the same way as Komponent does, but it's still a great improvement on standard view partials.

### [Update Feb 2020]
Excited to see [this tweet](https://twitter.com/dhh/status/1225504208465670147) from [@dhh](https://twitter.com/dhh) where he says he mentions a "new paradigm for the front-end" in Rails that basecamp have extracted from their new service [hey](https://hey.com) that he's going to be talking about at RailsConf this year. 🤞 for it being some combination of what Github have been doing and Komponent!

Also, the `ActionView::Component` mentioned above is not being upstreamed into Rails itself, it is being renamed to `ViewComponent` to reflect this.  See [this github issue thread](https://github.com/github/actionview-component/issues/206) for more details and discussion around these ideas of components in Rails.

Part of me wonders whether the reason `ViewComponent` is not being upstreamed, is related to that tweet from dhh, and that there were similar ideas from both the basecamp and github teams.  We'll have to wait and see&hellip;  
