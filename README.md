# Twitch AI Stream Manager Assistant (nicknamed Sen AI Assistant)

Note: I've nicknamed this the Sen AI Assistant as a throwback to [Senshudo](https://twitter.com/senshudo).

This is a small prototype I built within a couple days using my knowledge of [Laravel](https://laravel.com/), [InertiaJS](https://inertiajs.com/), [Tailwind](https://tailwindcss.com/), VueJS and OpenAI, utilizing the alpha release of [NativePHP](https://nativephp.com/). It's primary goal is to help generate suggested titles, tags, go live notifications around the category and activity you provide.

## OpenAI API Key Creation

Create an OpenAI account and head over to [https://platform.openai.com/api-keys](https://platform.openai.com/api-keys) create a key and paste the key into the field `OpenAI API Key` you can also provide your Organisation ID which can be found under [https://platform.openai.com/account/organization](https://platform.openai.com/account/organization).

Next you'll need to add some credits to your account which can be done from the [Billing](https://platform.openai.com/account/billing/overview) section, keep in mind the pricing for this model is pretty cheap so `$10` should be sufficient depending on your usage. N.B: You can setup a monthly budget limit under the [Limits](https://platform.openai.com/account/limits) section.

## Initial Configuration

Once the app is installed you'll be asked for your Twitch Username, OpenAI API Key and you can optionally provide the organisation ID.

## Generating Suggestions

- Click `New Stream`
- Enter your Category, an example `World of Warcraft` or `Halo Infinite`
- Enter your activity, an example `Playing Season of Discovery with friends` or `Playing Team Slayer with FreebirdTV`
- Click `Generate Suggestions` and wait

Note: If generation of suggestion fail at anypoint there will be a retry button on both the list view and detail view of the suggestions, this will attempt to regenerate the suggestions for the given values. 

## Developer Note:

NativePHP is currently in alpha release 

