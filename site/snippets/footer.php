<section class="flow">
    <theme-switcher>
        <fieldset>
            <legend><h4 class="">Theme</h4></legend>
            <div class="controls">
                <button type="button" value="auto" aria-current="true" data-has-accents="true" class="no-color">
                    Auto
                </button>
                <button type="button" value="light" aria-current="false" data-has-accents="true" class="no-color">
                    Light               
                </button>
                <button type="button" value="dark" aria-current="false" data-has-accents="true" class="no-color">
                    Dark
                </button>
                <button type="button" value="red" aria-current="false" style="--button-bg: var(--red);" data-has-accents="false">Red</button>
                <button type="button" value="blue" aria-current="false" style="--button-bg: var(--blue);" data-has-accents="false">Blue</button>
                <button type="button" value="yellow" aria-current="false" style="--button-bg: var(--yellow);" data-has-accents="false">Yellow</button>
                <button type="button" value="purple" aria-current="false" style="--button-bg: var(--purple);" data-has-accents="false">Purple</button>
                <button type="button" value="pink" aria-current="false" style="--button-bg: var(--pink);" data-has-accents="false">Pink</button>
                <button type="button" value="green" aria-current="false" style="--button-bg: var(--green);" data-has-accents="false">Green</button>
            </div>
        </fieldset>
    </theme-switcher>
<accent-switcher hidden>
            <fieldset>
            <legend><h4 class="">Accent colour</h4></legend>
            <div class="controls">
                <button type="button" value="" aria-current="true" class="no-color" default>
        None
    </button>
    <button type="button" value="red" aria-current="false" style="--button-bg: var(--red);">Red</button>
                <button type="button" value="blue" aria-current="false" style="--button-bg: var(--blue);">Blue</button>
                <button type="button" value="yellow" aria-current="false" style="--button-bg: var(--yellow);">Yellow</button>
                <button type="button" value="purple" aria-current="false" style="--button-bg: var(--purple);">Purple</button>
    <button type="button" value="pink" aria-current="false" style="--button-bg: var(--pink);">Pink</button>
                <button type="button" value="green" aria-current="false" style="--button-bg: var(--green);">Green</button>
                                </div>
            </fieldset>
</accent-switcher>

<p><small>&copy; <?= date('Y') ?> Chris Pymm · Made with <a href="https://getkirby.com">Kirby</a></small></p>
</section>

