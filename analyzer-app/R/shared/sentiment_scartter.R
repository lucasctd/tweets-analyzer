library('plotly')

source("shared/database.r")
source("shared/color.r")

renderSentimentScartterChart <- function(pre_candidato_id, nome_pre_candidato){
    
  query <- paste("SELECT score, DATE_FORMAT(tweet_criado_em,'%d/%m/%Y %H:%i') AS criado_em FROM V_SENTIMENTOS WHERE precandidato_id = ", pre_candidato_id)
  
  query <- paste(query, " ORDER BY tweet_criado_em", sep = '')
  
  res <- dbSendQuery(con, query)
  
  sentiment <- fetch(res, n = -1)
  
  
  ruim <- sentiment[which(sentiment$score < -0.25),]
  neutro <- sentiment[which(sentiment$score <= 0.25 & sentiment$score >= -0.25),]
  bom <- sentiment[which(sentiment$score > 0.25),]
  
  p <- plot_ly(x = as.POSIXct(bom$criado_em, format = '%d/%m/%Y %H:%M'), y = bom$score, name = paste('Bom ', '(', length(bom$score), ')', sep=''), type = 'scatter', mode = 'markers', marker = list(color = green), width = 1600, height = 900) %>%
              add_trace(x = as.POSIXct(neutro$criado_em, format = '%d/%m/%Y %H:%M'), y = neutro$score, name = paste('Neutro ', '(', length(neutro$score), ')', sep=''), mode = 'markers', marker = list(color = orange)) %>%
              add_trace(x = as.POSIXct(ruim$criado_em, format = '%d/%m/%Y %H:%M'), y = ruim$score, name = paste('Ruim ', '(', length(ruim$score), ')', sep=''), mode = 'markers', marker = list(color = red)) %>%
              layout(margin = list(l = 50, r = 50, b = 150, t = 50, pad = 4),
                     yaxis = list(autotick = F, dtick = 0.25),
                     title = paste('Sentimento nos tweets (', length(sentiment$score), ') relacionados \u00e0 ', nome_pre_candidato, sep=''))
  return(p)
}