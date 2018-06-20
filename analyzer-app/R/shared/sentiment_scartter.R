library('plotly')

source("shared/database.r")
source("shared/color.r")

query <- "SELECT score, DATE_FORMAT(tweet_criado_em,'%d/%m/%Y %H:%i') AS tweet_criado_em  FROM V_SENTIMENTOS WHERE hashtag = "
query <- paste(query, "'#", hashtags[1], "'", sep = '')

for(hashtag in hashtags[-1]){
  query <- paste(query, " OR hashtag = '#", hashtag, "'", sep = '')
}

query <- paste(query, " ORDER BY tweet_criado_em", sep = '')

res <- dbSendQuery(con, query)

sentiment <- fetch(res, n = -1)

ruim <- sentiment[which(sentiment$score <= -0.25),]
neutro <- sentiment[which(sentiment$score <= 0.25 & sentiment$score > -0.25),]
bom <- sentiment[which(sentiment$score > 0.25),]

p <- plot_ly(x = bom$tweet_criado_em, y = bom$score, name = 'Bom', type = 'scatter', mode = 'markers', marker = list(color = green), width = 1024, height = 768) %>%
            add_trace(x = neutro$tweet_criado_em, y = neutro$score, name = 'Neutro', mode = 'markers', marker = list(color = orange)) %>%
            add_trace(x = ruim$tweet_criado_em, y = ruim$score, name = 'Ruim', mode = 'markers', marker = list(color = red)) %>%
            layout(margin = list(l = 50, r = 50, b = 150, t = 50, pad = 4))