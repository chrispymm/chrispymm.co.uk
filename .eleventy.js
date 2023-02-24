
const { EleventyServerlessBundlerPlugin } = require("@11ty/eleventy");
const moment = require("moment");
const CleanCSS = require("clean-css");

const pluginRss = require("@11ty/eleventy-plugin-rss");
const syntaxHighlight = require("@11ty/eleventy-plugin-syntaxhighlight");

module.exports = function(eleventyConfig) {
  eleventyConfig.setTemplateFormats([
    "md",
    "njk",
    "css",
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

   eleventyConfig.addPlugin(EleventyServerlessBundlerPlugin, {
    name: "dynamic", // The serverless function name from your permalink object
    functionsDir: "./netlify/functions/",
    copy: [
      // files/directories that start with a dot
      // are not bundled by default
      // { from: ".cache", to: "cache" }
    ],
  });

  // When `permalink` is false, the file is not written to disk
	eleventyConfig.addGlobalData("eleventyComputed.permalink", function() {
		return (data) => {
			// Always skip during non-watch/serve builds
			if(data.draft && !process.env.BUILD_DRAFTS) {
				return false;
			}

			return data.permalink;
		}
	});

  // When `eleventyExcludeFromCollections` is true, the file is not included in any collections
	eleventyConfig.addGlobalData("eleventyComputed.eleventyExcludeFromCollections", function() {
		return (data) => {
			// Always exclude from non-watch/serve builds
			if(data.draft && !process.env.BUILD_DRAFTS) {
				return true;
			}

			return data.eleventyExcludeFromCollections;
		}
	});

	eleventyConfig.on("eleventy.before", ({runMode}) => {
		// Set the environment variable
		if(runMode === "serve" || runMode === "watch") {
			process.env.BUILD_DRAFTS = true;
		}
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
