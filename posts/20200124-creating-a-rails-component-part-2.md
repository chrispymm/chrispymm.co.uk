---
title: Creating a Rails Component using Komponent Part 2
permalink: posts/creating-rails-component-with-komponent-part-2/
date: 2020-01-24
tags: post
---

Last time I walked through setting up a simple notice component that could display messages to a user, and communicate status using icons and color.  In this post we'll devlop that component further to add some interactivity with Stimulus JS.

<!--more-->

At the end of the last post we had a notice component with the following code:
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

There are a couple of extra things I'd like to add to this component to wrap things up.  I'd like the option to allow users to be able to dismiss the notices, and I'd also like the option for them to be ephemeral flash messages that auto-dismiss themselves after a given time.

### Adding Interactivity using Stimulus

If you're not familiar [Stimulus JS](https://stimulusjs.org/) is a javascript 'framework' developed by the basecamp team.  It describes itself as

> A modest JavaScript framework for the HTML you already have

I'd encourage you take a read through the [handbook](https://stimulusjs.org/handbook/introduction) and the [reference](https://stimulusjs.org/reference/controllers) before reading further.  It shouldn't take long.  Stimulus is pretty simple and it's concepts are very easy to get your head around.

Stimulus is a great fit for components as stimulus controllers are automatically 'scoped' to an HTML element.  So we can simply add a controller to our component element and add javascript interactions scoped to it.

The rest of this post will assume you both installed Komponent, and generated your comonent with the `--stimulus` option.

So, to get started let's add things to our component helper to get this setup.

```rb
# frontend/components/notice/notice_component.rb

DISMISS_AFTER = 3000

property :dismissible, default: false
property :flash, default: false

def component_class_string
  "notice #{status_class} #{status_class}"
end

def flash_class
  @flash ? " flash" : ""
end

def dismissible?
  @dismissible
end

def data_attributes
  base_data_attributes.merge( auto_dismiss_data_attributes )
end

def base_data_attributes
  { controller: 'notice' }
end

def auto_dismiss_data_attributes
  @flash ? { 'notice-auto-dismiss': DISMISS_AFTER } : {}
end
```
There's a lot in the above block, so lets go through it.  First we add 2 properties; `:dismissible` is the control for whether the user can manually dismiss a notice, `:flash` will set wether the notice will auto dismiss.  We have also set a constant `DISMISS AFTER` to hold the timeout for our flash messages.

We've added a method for setting a class based on if we're displaying a flash message and another method to produce a concatenated class string for the component, a simple query method for if the component is dismissible, and then the next 3 methods handle setting up a hash of data attributes we need for stimulus integration.

Now lets update the component partial to incorporate these changes too.

```erb
# /frontend/components/_notice.html
<%= content_tag(:div, class: component_class_string, data: data_attributes) do %>
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
  <% if dismissible? %>
    <button class="c-notice__dismiss" data-action="click->notice#dismiss">
      <i class="fas fa-times" ></i>
    </button>
  <% end %>
<% end %>
```

I've amended the wrapper div to be a `content_tag` to allow for easier adding of the data_attributes.  Then we add a dismiss button if the component is dimissible.  The data-action on this element is how we hook into our stimulus controller. See the [actions reference](https://stimulusjs.org/reference/actions) page in the stimulus docs for further details.

Now we need to setup our stimulus controller to use these data attributes.

```js
# frontend/components/notice/notice_controller.js
import { Controller } from "stimulus";

export default class extends Controller {

  connect() {
    if( this.data.has("autoDismiss") ) {
      window.setTimeout(() => this.dismiss(), this.timeout);
    }
  }

  dismiss() {
    const notice = this.element
    notice.parentNode.removeChild(notice);
  }

  get timeout() {
    return parseInt(this.data.get("autoDismiss"));
  }
}
```

As it might not be obvious at first glance what's going on above, lets break it down.  We'll start with the `dismiss()` method.

Recall that we have a dismiss button in our partial that looks like this:
```html
button class="c-notice__dismiss" data-action="click->notice#dismiss">
```
The data-action attribute is where the magic happens, the `click->notice#dismiss` is what stimulus calls an **action descriptor**.  It has the format `event->controller#method`.  So our data-action attribute tells stimulus to listen for click events on this element, and when a click event is triggered to call the `dismiss()` method in the `notice_controller`.

In our dismiss method, we simply set the variable `notice` to `this.element`.  In a stimulus controller `this.element` refers to the element the controller is attached to, i.e. in our case the element with the data-attribute 
```
data-controller="notice"
```
All our method does is simply remove the notice from the DOM, thereby getting rid of the notice.  N.B. you can do whatever you like here to dismiss the notice.  In my production code the `removeChild` call is wrapped by some code that fades it out and slides it up.  

The other two methods in the controller are responsible for the auto-dismissing flash messages.  The `get timeout()` is a getter method that reads the timeout from the `notice-auto-dismiss` data attribute, and returns it as an integer.  Two imporatant things to note here are that the data attribute `notice-auto-dismiss` is automatically transformed into `autoDismiss` in the data map for the controller.  i.e. the controller name is removed, and kebab-case is transformed to camelCase.  The second thing is that all data attributes are strings, so you need to cast them to a different type if required. See the [data map reference](https://stimulusjs.org/reference/actions) page in the stimulus docs for further details. 

Lastly, the `connect()` method is a special lifecycle callback method available in stimulus controllers. The others are `initialize()` and `disconnect()`.  See the [lifecycle callbacks](https://stimulusjs.org/reference/actions) page in the stimulus docs for further details. 

The `connect()` method is called when both of the following conditions are true:

* its element is present in the document (i.e. a descendant of document.documentElement, the <html> element)
* its identifier is present in the element’s data-controller attribute

So, as soon as our message element appears in the DOM, Stimulus will call the `connect()` method in our controller.  Then we simply check to see if the component has the `notice-auto-dismiss` data attribute present, and if it does we set a timeout to call the `dismiss()` method after the provided timeout has elapsed.

So there we have it, a fully implemented, self-contained notice component that can be called anywhere in the views in our app, in a variety of different ways.  However the display of the component will always be consistent, and if we want to make any changes to how our notices display or behave, we only have to do it one one place.

Hopefully these articles have been helpful in introducing you to the possibilities of components within rails **without** the need for a full front-end framework.  I have found that structuring our app this way has been massively helpful in both simplifying our view code, and making it far easier to make changes - knowing that any change to a component automatically updates it across the whole app.






























