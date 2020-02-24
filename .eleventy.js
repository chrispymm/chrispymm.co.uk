
const moment = require("moment");
const CleanCSS = require("clean-css");

const pluginRss = require("@11ty/eleventy-plugin-rss");
const syntaxHighlight = require("@11ty/eleventy-plugin-syntaxhighlight");

module.exports = function(eleventyConfig) {
  eleventyConfig.setTemplateFormats([
    "md",
    "njk",
    "css",
    "js"
  ]);

  eleventyConfig.addPassthroughCopy("assets/images");
  eleventyConfig.addPassthroughCopy("assets/fonts");

  eleventyConfig.addPlugin(syntaxHighlight);
  eleventyConfig.addPlugin(pluginRss);


  eleventyConfig.addShortcode('excerpt', post => extractExcerpt(post));

  eleventyConfig.addFilter("cssmin", function(code) {
    return new CleanCSS({}).minify(code).styles;
  });


  eleventyConfig.setLiquidOptions({
    dynamicPartials: true,
  });

  eleventyConfig.addNunjucksFilter("limit", function(array, limit) {
    return array.slice(0, limit);
  });
  
  // date filter
  eleventyConfig.addNunjucksFilter("date", function(date, format) {
    return moment(date).format(format);
  });

};

/**
 * Extracts the excerpt from a document.
 *
 * @param {*} doc A real big object full of all sorts of information about a document.
 * @returns {String} the excerpt.
 */
function extractExcerpt(doc) {
  if (!doc.hasOwnProperty('templateContent')) {
    console.warn('❌ Failed to extract excerpt: Document has no property `templateContent`.');
    return;
  }

  const excerptSeparator = '<!--more-->';
  const content = doc.templateContent;

  if (content.includes(excerptSeparator)) {
    return content.substring(0, content.indexOf(excerptSeparator)).trim();
  }

  const pCloseTag = '</p>';
  if (content.includes(pCloseTag)) {
    return content.substring(0, content.indexOf(pCloseTag) + pCloseTag.length);
  }

  return content;
}