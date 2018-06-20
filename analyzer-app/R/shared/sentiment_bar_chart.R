library('magrittr')
library('plotly')

source("shared/sentiment.r")
source("shared/color.R")

calcPercentage <- function(qtd, total){
  return(qtd / total * 100)
}

renderSentimentBarChart <- function(hashtags, nome_candidato){
  sentiment <- getSentiment(hashtags)

  ruim <- with(sentiment, c(sum(sentiment$score <= -0.25 )))
  neutro <- with(sentiment, c(sum(sentiment$score <= 0.25 & sentiment$score > -0.25)))
  bom <- with(sentiment, c(sum(sentiment$score > 0.25 )))

  values <- c(ruim, neutro, bom)
  total <- sum(values)
  text <- lapply(values, function (x) round(calcPercentage(x, total), 4))
  text <- paste(text, '%', sep = '')

  p <- plot_ly(
    x = c("Ruim", "Neutro", "Bom"),
    y = values,
    text = text,
    textposition = 'auto',
    type = "bar",
    marker = list(color = c(red, orange, green))
  )%>%
  layout(title = paste('Sentimento nos Tweets Relacionados \u00e0', nome_candidato),
         yaxis = list(title = 'N\u00famero de Tweets'), xaxis = list(title = paste('Tweets analizados:', total)))

  return(p)
}