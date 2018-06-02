source("shared/feeling.r")

tonumber <- function(x){
  return(as.numeric(levels(x))[x])
}

calcPercentage <- function(qtd, total){
  return(qtd / total * 100)
}

renderFeelingBarChart <- function(hashtag1, hashtag2, hashtag3, nome_candidato){

  feeling <- getFeeling(hashtag1, hashtag2, hashtag3)

  ltdot25 <- with(feeling, c(sum(tonumber(feeling$score) <= .25 )))
  ltdot5 <- with(feeling, c(sum(tonumber(feeling$score) <= .5 & tonumber(feeling$score) > .25)))
  ltdot75 <- with(feeling, c(sum(tonumber(feeling$score) <= .75 & tonumber(feeling$score) > .5)))
  gt75 <- with(feeling, c(sum(tonumber(feeling$score) > .75 )))

  total <- ltdot25 + ltdot5 + ltdot75 + gt75
  values <- c(ltdot25, ltdot5, ltdot75, gt75)
  text <- lapply(values, function (x) round(calcPercentage(x, total), 4))
  text <- paste(text, '%', sep = '')
  #c("<= 0.25", "> 0.25 & <= 0.5", "> 0.5 & <= 0.75", "> 0.75")
  p <- plot_ly(
    x = c("P\u00e9ssimo", "Ruim", "Bom", "\u00d3timo"),
    y = values,
    text = text,
    textposition = 'auto',
    type = "bar",
    marker = list(color = c('#FF6347', 'orange', '#00FFFF', '#7FFF00'))
  )%>%
  layout(title = paste('Sentimento dos Tweets Relacionados \u00e0', nome_candidato),
         yaxis = list(
           title = 'N\u00famero de Tweets')
         )

  return(p)
}