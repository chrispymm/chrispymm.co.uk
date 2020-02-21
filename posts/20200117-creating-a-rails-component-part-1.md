---
title: Creating a Rails Component using Komponent 
permalink: "posts/{{ title | slug }}/index.html"
date: 2020-01-17
---

Following on from my last post about setting up a component based view system in Rails, I thought it would be good to share an example of building out a component in rails with the Komponent gem.

<!--more-->

In this article I'll walk thorugh creating a 'notice' component used in the interface to display messages to the user, somehting like the below.

![Notice component examples](/assets/images/notice-components.png)

To start off with, ensure you've added the komponent gem to your gemfile and run `bundle install`. Run the below command to install and configure komponnet in your project.  As you might guess the `--stimulus` option sets things up to use the stimulus js library.

```
rails generate komponent:install --stimulus
```

Komponent provides a generator to create all the files we need for a component.  For our component we need to run:

```
rails generate component notice --stimulus
```

The stimulus option will genearte us a stimulus controller for this component, which we'll be using to add a bit of interactivity later.

Before we start coding, lets think about what we know about our component. We know that the user needs to be able to supply a message, we need to handle different statuses such as 'success' and 'error', and we need to be able to display different colors and icons alongside the messages.

### Displaying Messages

At its simplest we need to be able to display a message from the user within our component. So lets write the partial for that.

```erb
# /frontend/components/_notice.html
<div class="notice">
  <%= @message %>
</div>

```

We can now use our component in our view with the following syntax

```erb
<%= c("notice", message: "User was saved successfully") %>
```

The `:message` argument in our component render call is automatically passed through to become the instance variable in our view.

We often want a bit more control than that though, and this is where the component helper modules come in.  Let's say we want to make our message property required, which seems pretty sensible, we don't want to display blank notice boxes to the user. Open up the `notice_component.rb` and add the following property declaration to the file.

```rb
# frontend/components/notice/notice_component.rb
module NoticeComponent
  extend ComponentHelper

  property :message, required: true

end
```

Now if we try and call our component without a message it will raise an error. 

There are occasions where it is helpful to provide a little bit more than a one line message, so lets amend our component so we can provide a block containing more details.

```erb
# /frontend/components/_notice.html
<div class="notice">
  <% if block_given_to_component? %>
    <h4><%= @message %></h4>
    <p><%= yield %></p>
  <% else %>
    <%= @message %>
  <% end %>
</div>

```

The `block_given_to_component?` method is a helper provided by the komponent gem to determine if a block has been passed.  This allows us to change the output to use the message as the title, and the block content as the body of the notice.  this can be called in our views using the following.

```erb
<%= c("notice", message: "Something to think about") do  %>
  Are you really sure you want to do this?  There might be some consequences...
<% end %>
```
### Displaying different Statuses

At this point we've got a super basic component that can display a message to the user.  Not overly helpful yet, so let's handle different statuses.  We want 3 statuses, 'info', 'success' and 'error' and each of these will change the color and the icon displayed with the message.  If no status is provided, we'll default to 'info'.

```rb
# frontend/components/notice/notice_component.rb
module NoticeComponent
  extend ComponentHelper

  STATUS_MAPPINGS = {
    info: { class: ' info', icon: ' fa-info-circle' },
    success: { class: ' success', icon: ' fa-check-circle' },
    error: { class: ' error', icon: ' fa-exclamation-circle' },
    default: { class: ' info', icon: ' fa-info-circle' }
  }.freeze

  property :message, required: true
  property :status, default: :default

  def icon_class
    STATUS_MAPPINGS[@status][:icon]
  end

  def status_class
    STATUS_MAPPINGS[@status][:class]
  end

end
```

In the above code we've added a constant to hold our statuses and how they map to icons and colors (I'm using font-awesome for icons, and i'm using a class on the whole notice to handle the color change). We've also defined a couple of helper methods to anable quick access to the correct color and icon class depending on the status provide to the component.  Our component partial now looks like

```erb
# /frontend/components/_notice.html
<div class="notice #{ status_class }">
  <div class="notice__icon">
    <i class="fas <%= icon_class %>"></i>
  </div>  
  <div class="notice__content">
    <% if block_given_to_component? %>
      <h4><%= @message %></h4>
      <p><%= yield %></p>
    <% else %>
      <%= @message %>
    <% end %>
  </div>
</div>
```

We've added a few extra divs to handle laying out the content and icons using flexbox, but you can see that we can simply call the methods in the component helper easily within the view partial.

We've now got a really helpful reusable component that we can use throughout our application, to display messages and status updates to the user.  I've purposefully not covered styling in this post.  But we can now style our component really easily by adding our styles into the `frontend/components/notice/notice.css` file, using whatever flavour of CSS you like (ITCSS, BEM, utiltity classes).  As I mentioned in the [previous post](/posts/using-components-in-rails-without-a-frontend-framework/), this component based setup pairs really well with utility-class based css such as [Tailwind](https://tailwindcss.com) or [Tachyons](https://tachyons.io/).  

In my next post I'll look at devloping this component further to add interectivity using Stimulus JS.




















