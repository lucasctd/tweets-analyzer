library(plotly)
library(magrittr)

source("shared/database.r")
source("all/functions.r")

getTop5HashtagsPerUser = function(username){
  query = "SELECT hu.name AS name, COUNT(hu.name) AS qtd FROM tweet_owner towner
          INNER JOIN tweet tw ON towner.id = tw.owner_id
          INNER JOIN hashtag_username hu ON tw.id = hu.tweet_id
          WHERE towner.screen_name = 'param'
          GROUP BY name
          ORDER BY qtd DESC"
  res <- dbSendQuery(con, sub('param', username, query))
  data <- fetch(res, n = -1)
  dfTotal <- data.frame("name" = data$name, "qtd" = data$qtd)
  dfTop5 <- dfTotal[1:5,]
  dfOutros <- data.frame("name" = "Outros", "qtd" = sum(dfTotal[6:length(dfTotal$qtd),]$qtd))
  dfresult <- rbind(dfTop5, dfOutros)
  #dfresult$name <- factor(dfresult$name)
  dfresult$name <- sapply(dfresult$name, as.character)
  total <- sum(dfresult$qtd)
  dfresult$percent <- sapply(dfresult$qtd, function(x){round(calcPercentage(x, total), 2)})
  dfresult$qtd <- NULL #drop qtd collumn
  return (dfresult)
}

percentToUnit <- function(percent, total){
  return(round(percent * total / 100))
}

makeVectorOfQtds <- function(hashtagNum){
  return(sapply(vector[[hashtagNum * 2]], function(percent){ percentToUnit(percent,  dataFrame$qtd[hashtagNum]) }))
}

query <- "SELECT screen_name AS name, qtd FROM V_TOP5_USERS"

res <- dbSendQuery(con, query)

data <- fetch(res, n = -1)

dataFrame <- data.frame("name" = data$name, "qtd" = data$qtd)

vector <- sapply(data$name, getTop5HashtagsPerUser)#retorna numeros agrupados em vetores, os indices impares sao de nomes e pares sao de percentual

hashtagsQtdsPercent <- matrix( c(vector[[2]], vector[[4]], vector[[6]], vector[[8]], vector[[10]]), byrow = FALSE, nrow = 6)

hashtagsNames <- matrix( c(vector[[1]], vector[[3]], vector[[5]], vector[[7]], vector[[9]]), byrow = FALSE, nrow = 6)

hashtagsQtds <- matrix(c(makeVectorOfQtds(1), makeVectorOfQtds(2), makeVectorOfQtds(3), makeVectorOfQtds(4), makeVectorOfQtds(5)), byrow = FALSE, nrow = 6)

p <- plot_ly(
  x = paste(dataFrame$name, paste(dataFrame$qtd, 'tweets'), sep = ' - '),
  y = hashtagsQtds[1, ],
  textposition = 'auto',
  text = paste(hashtagsNames[1, ], paste(hashtagsQtdsPercent[1, ], '%', sep = ''), sep = ' - '),
  type = "bar",
  marker = list(color = c('#87CEFA'))
)%>%
  add_trace(y = hashtagsQtds[2, ], text = paste(hashtagsNames[2, ], paste(hashtagsQtdsPercent[2, ], '%', sep = ''), sep = ' - '), marker = list(color = c('#00FF7F'))) %>%
  add_trace(y = hashtagsQtds[3, ], text = paste(hashtagsNames[3, ], paste(hashtagsQtdsPercent[3, ], '%', sep = ''), sep = ' - '), marker = list(color = c('#FA8072'))) %>%
  add_trace(y = hashtagsQtds[4, ], text = paste(hashtagsNames[4, ], paste(hashtagsQtdsPercent[4, ], '%', sep = ''), sep = ' - '), marker = list(color = c('#BDB76B'))) %>%
  add_trace(y = hashtagsQtds[5, ], text = paste(hashtagsNames[5, ], paste(hashtagsQtdsPercent[5, ], '%', sep = ''), sep = ' - '), marker = list(color = c('orange'))) %>%
  add_trace(y = hashtagsQtds[6, ], text = paste(hashtagsNames[6, ], paste(hashtagsQtdsPercent[6, ], '%', sep = ''), sep = ' - '), marker = list(color = c('#CDB79E'))) %>%
  layout(title = 'Usu\u00e1rios mais ativos', yaxis = list(title = 'Qtd. de Tweets'),
                                              xaxis = list(title = 'Usu\u00e1rios / Total de Tweets'), barmode = 'stack', showlegend = FALSE)