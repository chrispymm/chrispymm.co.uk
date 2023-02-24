const GithubApi = require('@octokit/rest');
const { Octokit } = require("@octokit/core");
const cookie = require("cookie");
const { tokens, getCookie } = require("./util/auth.js");
const querystring = require('querystring');
const { base64encode } = require('nodejs-base64');
const fm = require('front-matter');

exports.handler = async (event, context) => {
  const [owner, repo] = process.env.GITHUB_REPO.split('/');
  console.log(event)
  let authToken;
  let provider;

  if(event.headers && event.headers.cookie) {
    let cookies = cookie.parse(event.headers.cookie);
    if(cookies._11ty_oauth_token) {
      authToken = tokens.decode(cookies._11ty_oauth_token);
    }
    if(cookies._11ty_oauth_provider) {
      provider = cookies._11ty_oauth_provider;
    }
  }
  if( authToken ) {

    const github = new Octokit({
        auth: `token ${authToken}`,
        request: {
          timeout: 5000
        }
      });

    let formData = querystring.decode(event.body)
    const content = formData.content;
    const frontmatter = fm(content);
    const date = new Intl.DateTimeFormat('en-GB').format(date).split('/').reverse().join('-');
    const title = frontmatter.attributes.title
    const fullTitle = `${date}-${frontmatter.attributes.title}`;

    if(!title) {
      return {
        statusCode: 500,
        body: JSON.stringify({ message: "No title present in content" }),
      };
    }

    const path = `posts/${fullTitle.replace(/\s/g, '-').toLowerCase()}.njk`

    const response = await github.request('PUT /repos/{owner}/{repo}/contents/{path}', {
      owner: owner,
      repo: repo,
      path: path,
      message: `Add Post: ${title}`,
      content: base64encode(newContent),
    });

    if(response.status < 300) {
      return {
        statusCode: 200,
        body: JSON.stringify({ message: "Success" }),
      };
    } else {
      return {
        statusCode: response.status,
        body: JSON.stringify({ message: "Error" }),
      };
    }
  }



}
