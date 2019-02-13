// vue.config.js
module.exports = {
  
  publicPath: process.env.BASE_URL,//process.env.NODE_ENV === 'production' ? './' : '',
  filenameHashing: false,
  chainWebpack: config => {
    config.module
      .rule('images')
        .use('url-loader')
          .loader('url-loader')
          .tap(options => Object.assign(options, { limit: 100240 }))
  }
}